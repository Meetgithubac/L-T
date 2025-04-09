<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\LaundryService;
use App\Models\TiffinService;
use App\Models\User;
use App\Notifications\OrderStatusNotification;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index()
    {
        // Different users see different orders
        if (auth()->user()->hasRole('admin')) {
            $orders = Order::with('user')->latest()->paginate(10);
        } elseif (auth()->user()->hasAnyRole(['service_provider', 'laundry_provider', 'tiffin_provider'])) {
            $orders = Order::with('user')
                ->where('assigned_to', auth()->id())
                ->orWhereHas('items.service', function($query) {
                    $query->where('provider_id', auth()->id());
                })
                ->latest()
                ->paginate(10);
        } else {
            // Customer
            $orders = Order::where('user_id', auth()->id())->latest()->paginate(10);
        }
        
        return view('orders.index', compact('orders'));
    }
    
    /**
     * Show the form for creating a new order.
     */
    public function create()
    {
        $this->authorize('create orders');
        
        $laundryServices = LaundryService::where('is_available', true)->get();
        $tiffinServices = TiffinService::where('is_available', true)->get();
        
        return view('orders.create', compact('laundryServices', 'tiffinServices'));
    }
    
    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create orders');
        
        $validated = $request->validate([
            'delivery_address' => 'required|string',
            'delivery_phone' => 'required|string',
            'delivery_time' => 'required|date',
            'special_instructions' => 'nullable|string',
            'payment_method' => 'required|in:cash_on_delivery,online_payment,cash,card,upi',
            'delivery_latitude' => 'nullable|numeric|between:-90,90',
            'delivery_longitude' => 'nullable|numeric|between:-180,180',
            'laundry_services' => 'array',
            'laundry_services.*.id' => 'exists:laundry_services,id',
            'laundry_services.*.quantity' => 'integer|min:1',
            'tiffin_services' => 'array',
            'tiffin_services.*.id' => 'exists:tiffin_services,id',
            'tiffin_services.*.quantity' => 'integer|min:1',
            'instructions' => 'nullable|string',
        ]);
        
        // Ensure at least one service is selected
        if (empty($validated['laundry_services']) && empty($validated['tiffin_services'])) {
            return back()->withErrors(['service' => 'You must select at least one service.'])->withInput();
        }
        
        // Determine order type
        $orderType = 'mixed';
        if (empty($validated['laundry_services'])) {
            $orderType = 'tiffin';
        } elseif (empty($validated['tiffin_services'])) {
            $orderType = 'laundry';
        }
        
        // Calculate total amount
        $totalAmount = 0;
        
        // Create the order
        $order = new Order([
            'user_id' => auth()->id(),
            'order_number' => Order::generateOrderNumber(),
            'order_type' => $orderType,
            'status' => 'pending',
            'total_amount' => $totalAmount, // Will be updated after adding items
            'delivery_address' => $validated['delivery_address'],
            'delivery_phone' => $validated['delivery_phone'],
            'delivery_time' => $validated['delivery_time'],
            'special_instructions' => $validated['special_instructions'] ?? null,
            'payment_method' => $validated['payment_method'],
            'is_paid' => false,
            'delivery_latitude' => $validated['delivery_latitude'] ?? null,
            'delivery_longitude' => $validated['delivery_longitude'] ?? null,
        ]);
        
        $order->save();
        
        // Add laundry services to order items
        if (!empty($validated['laundry_services'])) {
            foreach ($validated['laundry_services'] as $item) {
                $service = LaundryService::findOrFail($item['id']);
                $quantity = $item['quantity'];
                $unitPrice = $service->price;
                $subtotal = $unitPrice * $quantity;
                
                $orderItem = new OrderItem([
                    'order_id' => $order->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                ]);
                
                $orderItem->service()->associate($service);
                $orderItem->save();
                
                $totalAmount += $subtotal;
            }
        }
        
        // Add tiffin services to order items
        if (!empty($validated['tiffin_services'])) {
            foreach ($validated['tiffin_services'] as $item) {
                $service = TiffinService::findOrFail($item['id']);
                $quantity = $item['quantity'];
                $unitPrice = $service->price;
                $subtotal = $unitPrice * $quantity;
                
                $orderItem = new OrderItem([
                    'order_id' => $order->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                ]);
                
                $orderItem->service()->associate($service);
                $orderItem->save();
                
                $totalAmount += $subtotal;
            }
        }
        
        // Update the total amount
        $order->total_amount = $totalAmount;
        $order->save();
        
        // Send notification to user about new order
        $order->user->notify(new OrderStatusNotification($order));
        
        return redirect()->route('orders.show', $order)
            ->with('success', 'Order created successfully. Your order number is ' . $order->order_number);
    }
    
    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $this->authorize('view orders');
        
        // Only allow users to view their own orders unless they're admin or the assigned provider
        if (auth()->user()->hasRole('customer') && 
            auth()->id() !== $order->user_id && 
            auth()->id() !== $order->assigned_to) {
            abort(403, 'Unauthorized action.');
        }
        
        $order->load('items.service', 'user', 'assignedProvider');
        
        return view('orders.show', compact('order'));
    }
    
    /**
     * Show the form for editing the order status.
     */
    public function edit(Order $order)
    {
        $this->authorize('edit orders');
        
        if (auth()->user()->hasRole('admin') || auth()->id() === $order->assigned_to) {
            $providers = User::whereHas('roles', function($query) {
                $query->whereIn('name', ['service_provider', 'laundry_provider', 'tiffin_provider']);
            })->get();
            
            return view('orders.edit', compact('order', 'providers'));
        }
        
        abort(403, 'Unauthorized action.');
    }
    
    /**
     * Update the order status.
     */
    public function update(Request $request, Order $order)
    {
        $this->authorize('edit orders');
        
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,delivered,cancelled',
            'assigned_to' => 'nullable|exists:users,id',
            'cancellation_reason' => 'required_if:status,cancelled',
        ]);
        
        if ($validated['status'] === 'cancelled' && empty($validated['cancellation_reason'])) {
            return back()->withErrors(['cancellation_reason' => 'Please provide a reason for cancellation.'])->withInput();
        }
        
        // Save previous status to check for changes
        $oldStatus = $order->status;
        
        $order->status = $validated['status'];
        $order->assigned_to = $validated['assigned_to'] ?? null;
        
        if ($validated['status'] === 'cancelled') {
            $order->cancellation_reason = $validated['cancellation_reason'];
        }
        
        $order->save();
        
        // Send notification to user about status change
        if ($oldStatus !== $validated['status']) {
            // Prepare appropriate message based on status
            $message = '';
            switch ($validated['status']) {
                case 'processing':
                    $message = 'Your order is now being processed.';
                    break;
                case 'completed':
                    $message = 'Your order has been completed and is ready for delivery.';
                    break;
                case 'delivered':
                    $message = 'Your order has been delivered. Thank you for using our services!';
                    break;
                case 'cancelled':
                    $message = 'Your order has been cancelled. Reason: ' . $validated['cancellation_reason'];
                    break;
                default:
                    $message = 'Your order status has been updated to: ' . $validated['status'];
            }
            
            // Add location information to message if processing or ready for delivery
            if (in_array($validated['status'], ['processing', 'ready for delivery'])) {
                // If the order has both current and delivery location, add distance
                if ($order->latitude && $order->longitude && $order->delivery_latitude && $order->delivery_longitude) {
                    $distance = $this->calculateDistance(
                        $order->latitude, 
                        $order->longitude,
                        $order->delivery_latitude,
                        $order->delivery_longitude
                    );
                    
                    // Format distance message
                    $distanceInfo = '';
                    if ($distance < 1) {
                        $distanceInMeters = round($distance * 1000);
                        $distanceInfo = " Order is approximately {$distanceInMeters} meters from delivery location.";
                    } else {
                        $distanceInfo = " Order is approximately " . number_format($distance, 1) . " km from delivery location.";
                    }
                    
                    $message .= $distanceInfo;
                }
            }
            
            $order->user->notify(new OrderStatusNotification($order, $message));
            
            // If the order is assigned to a provider, notify them too
            if ($order->assigned_to && $validated['status'] !== 'cancelled') {
                $provider = User::find($order->assigned_to);
                if ($provider) {
                    $provider->notify(new OrderStatusNotification($order, $message));
                }
            }
        }
        
        return redirect()->route('orders.show', $order)
            ->with('success', 'Order status updated successfully.');
    }
    
    /**
     * Cancel an order (customer functionality).
     */
    public function cancel(Request $request, Order $order)
    {
        $this->authorize('cancel orders');
        
        // Customers can only cancel their own orders
        if (auth()->id() !== $order->user_id) {
            abort(403, 'Unauthorized action.');
        }
        
        // Orders can only be cancelled if they are pending or processing
        if (!in_array($order->status, ['pending', 'processing'])) {
            return back()->withErrors(['cancel' => 'Orders can only be cancelled if they are pending or processing.']);
        }
        
        $validated = $request->validate([
            'cancellation_reason' => 'required|string',
        ]);
        
        $order->status = 'cancelled';
        $order->cancellation_reason = $validated['cancellation_reason'];
        $order->save();
        
        // Send notification to the user
        $order->user->notify(new OrderStatusNotification($order));
        
        // Notify assigned provider if there is one
        if ($order->assigned_to) {
            $provider = User::find($order->assigned_to);
            if ($provider) {
                $provider->notify(new OrderStatusNotification($order));
            }
        }
        
        return redirect()->route('orders.show', $order)
            ->with('success', 'Order cancelled successfully.');
    }
    
    /**
     * Update the location of an order for tracking
     */
    public function updateLocation(Request $request, Order $order)
    {
        $this->authorize('edit orders');
        
        // Only admin or the assigned provider can update location
        if (!auth()->user()->hasRole('admin') && auth()->id() !== $order->assigned_to) {
            abort(403, 'Unauthorized action.');
        }
        
        // Can only update location for processing or ready for delivery orders
        if (!in_array($order->status, ['processing', 'ready for delivery'])) {
            return response()->json(['error' => 'Can only update location for orders in processing or ready for delivery status'], 400);
        }
        
        $validated = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);
        
        $order->latitude = $validated['latitude'];
        $order->longitude = $validated['longitude'];
        $order->location_updated_at = now();
        $order->save();
        
        // Calculate distance to delivery location if set
        $distanceMessage = '';
        if ($order->delivery_latitude && $order->delivery_longitude) {
            $distance = $this->calculateDistance(
                $validated['latitude'], 
                $validated['longitude'],
                $order->delivery_latitude,
                $order->delivery_longitude
            );
            
            // Format distance message based on distance
            if ($distance < 1) {
                // Convert to meters if less than 1 km
                $distanceInMeters = round($distance * 1000);
                $distanceMessage = " Your order is approximately {$distanceInMeters} meters from the delivery location.";
            } else {
                $distanceMessage = " Your order is approximately " . number_format($distance, 1) . " km from the delivery location.";
            }
        }
        
        // Send notification to the user about location update
        $order->user->notify(new OrderStatusNotification(
            $order, 
            'Your order\'s location has been updated. You can now track it in real-time.' . $distanceMessage
        ));
        
        // Prepare response data with distance information if available
        $responseData = [
            'success' => true,
            'message' => 'Order location updated successfully',
            'data' => [
                'latitude' => $order->latitude,
                'longitude' => $order->longitude,
                'updated_at' => $order->location_updated_at->format('Y-m-d H:i:s')
            ]
        ];
        
        // Add delivery location and distance info if available
        if ($order->delivery_latitude && $order->delivery_longitude) {
            $responseData['data']['delivery'] = [
                'latitude' => $order->delivery_latitude,
                'longitude' => $order->delivery_longitude,
                'address' => $order->delivery_address
            ];
            
            // Calculate and include distance
            $distance = $this->calculateDistance(
                $order->latitude, 
                $order->longitude,
                $order->delivery_latitude,
                $order->delivery_longitude
            );
            
            $responseData['data']['distance'] = [
                'value' => $distance,
                'unit' => 'km',
                'formatted' => $distance < 1 
                    ? round($distance * 1000) . ' meters' 
                    : number_format($distance, 1) . ' km'
            ];
        }
        
        return response()->json($responseData);
    }
    
    /**
     * Calculate the distance between two sets of coordinates using the Haversine formula
     * 
     * @param float $lat1 Latitude of point 1
     * @param float $lon1 Longitude of point 1
     * @param float $lat2 Latitude of point 2
     * @param float $lon2 Longitude of point 2
     * @return float Distance in kilometers
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2) {
        // Convert latitude and longitude from degrees to radians
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);
        
        // Radius of the Earth in kilometers
        $radius = 6371;
        
        // Haversine formula
        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;
        $a = sin($dlat/2) * sin($dlat/2) + cos($lat1) * cos($lat2) * sin($dlon/2) * sin($dlon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $distance = $radius * $c;
        
        return $distance;
    }
}