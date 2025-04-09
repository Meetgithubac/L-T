@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" 
          crossorigin="" />
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" 
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" 
            crossorigin=""></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize map - default to delivery coordinates if tracking coordinates aren't available
            @if($order->delivery_latitude && $order->delivery_longitude)
                // Use delivery location coordinates if available
                const mapCenter = [{{ $order->delivery_latitude }}, {{ $order->delivery_longitude }}];
                const hasDeliveryCoordinates = true;
            @elseif($order->latitude && $order->longitude)
                // Fall back to tracking coordinates if delivery coordinates aren't available
                const mapCenter = [{{ $order->latitude }}, {{ $order->longitude }}];
                const hasDeliveryCoordinates = false;
            @else
                // If no coordinates are available, default to a general location (e.g., center of service area)
                const mapCenter = [20.5937, 78.9629]; // Default center of India
                const hasDeliveryCoordinates = false;
            @endif
            
            // Create map with determined center
            const map = L.map('order-tracking-map').setView(mapCenter, 15);
            
            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
            
            // Add marker for current order location if available
            @if($order->latitude && $order->longitude)
                const orderMarker = L.marker([{{ $order->latitude }}, {{ $order->longitude }}], {
                    icon: L.divIcon({
                        html: '<i class="fas fa-truck text-primary" style="font-size: 24px;"></i>',
                        className: 'order-location-marker',
                        iconSize: [24, 24],
                        iconAnchor: [12, 24]
                    })
                }).addTo(map);
                orderMarker.bindPopup("<b>Order #{{ $order->order_number }}</b><br>Status: {{ ucfirst($order->status) }}").openPopup();
            @endif
            
            // Add delivery location marker if available
            @if($order->delivery_latitude && $order->delivery_longitude)
                const deliveryMarker = L.marker([{{ $order->delivery_latitude }}, {{ $order->delivery_longitude }}], {
                    icon: L.divIcon({
                        html: '<i class="fas fa-home text-danger" style="font-size: 24px;"></i>',
                        className: 'delivery-location-marker',
                        iconSize: [24, 24],
                        iconAnchor: [12, 24]
                    })
                }).addTo(map);
                deliveryMarker.bindPopup("<b>Delivery Location</b><br>{{ $order->delivery_address }}").openPopup();
            @endif
            
            // If both markers exist, draw a line between them
            @if($order->latitude && $order->longitude && $order->delivery_latitude && $order->delivery_longitude)
                const routeLine = L.polyline([
                    [{{ $order->latitude }}, {{ $order->longitude }}],
                    [{{ $order->delivery_latitude }}, {{ $order->delivery_longitude }}]
                ], {
                    color: '#3388ff',
                    weight: 3,
                    opacity: 0.7,
                    dashArray: '10, 10',
                    lineJoin: 'round'
                }).addTo(map);
                
                // Fit map bounds to show both markers
                map.fitBounds(routeLine.getBounds(), {
                    padding: [50, 50]
                });
                
                // Calculate and display estimated distance and delivery time
                const lat1 = {{ $order->latitude }};
                const lon1 = {{ $order->longitude }};
                const lat2 = {{ $order->delivery_latitude }};
                const lon2 = {{ $order->delivery_longitude }};
                
                // Calculate distance using Haversine formula
                function calculateDistance(lat1, lon1, lat2, lon2) {
                    const R = 6371; // Radius of the earth in km
                    const dLat = deg2rad(lat2 - lat1);
                    const dLon = deg2rad(lon2 - lon1);
                    const a = 
                        Math.sin(dLat/2) * Math.sin(dLat/2) +
                        Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * 
                        Math.sin(dLon/2) * Math.sin(dLon/2); 
                    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
                    const distance = R * c; // Distance in km
                    return distance;
                }
                
                function deg2rad(deg) {
                    return deg * (Math.PI/180);
                }
                
                // Calculate distance
                const distance = calculateDistance(lat1, lon1, lat2, lon2);
                
                // Estimate delivery time (assuming average speed of 20 km/h in city traffic)
                const avgSpeedKmh = 20;
                const estimatedTimeHours = distance / avgSpeedKmh;
                
                // Convert to minutes if less than 1 hour
                let etaText = '';
                if (estimatedTimeHours < 1) {
                    const estimatedMinutes = Math.round(estimatedTimeHours * 60);
                    etaText = `${estimatedMinutes} minute${estimatedMinutes !== 1 ? 's' : ''}`;
                } else {
                    const hours = Math.floor(estimatedTimeHours);
                    const minutes = Math.round((estimatedTimeHours - hours) * 60);
                    etaText = `${hours} hour${hours !== 1 ? 's' : ''} ${minutes} minute${minutes !== 1 ? 's' : ''}`;
                }
                
                // Display the info
                const distanceFormatted = distance < 1 
                    ? `${Math.round(distance * 1000)} meters` 
                    : `${distance.toFixed(1)} km`;
                
                // Create a control to display the ETA
                const etaControl = L.control({position: 'bottomleft'});
                etaControl.onAdd = function() {
                    const div = L.DomUtil.create('div', 'info eta-info');
                    div.innerHTML = `
                        <div style="background: white; padding: 8px 12px; border-radius: 4px; box-shadow: 0 1px 5px rgba(0,0,0,0.4);">
                            <div><strong>Distance:</strong> ${distanceFormatted}</div>
                            <div><strong>Est. Delivery Time:</strong> ${etaText}</div>
                        </div>
                    `;
                    return div;
                };
                etaControl.addTo(map);
            @endif
            
            // Update timestamp in human-readable format
            @if($order->location_updated_at)
                const timestamp = document.getElementById('location-updated-time');
                if (timestamp) {
                    const now = new Date();
                    const updated = new Date('{{ $order->location_updated_at }}');
                    const diffMs = now - updated;
                    const diffMins = Math.round(diffMs / 60000);
                    
                    if (diffMins < 60) {
                        timestamp.textContent = `${diffMins} minute${diffMins !== 1 ? 's' : ''} ago`;
                    } else {
                        const diffHours = Math.round(diffMins / 60);
                        timestamp.textContent = `${diffHours} hour${diffHours !== 1 ? 's' : ''} ago`;
                    }
                }
            @endif
        });
        
        // For service providers to update location
        @if(auth()->user()->hasRole('admin') || auth()->id() === $order->assigned_to)
        function updateOrderLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    // Success callback
                    function(position) {
                        const latitude = position.coords.latitude;
                        const longitude = position.coords.longitude;
                        
                        // Send location update to server
                        fetch('{{ route("orders.update-location", $order->id) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                latitude: latitude,
                                longitude: longitude
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Show success message
                                alert('Location updated successfully!');
                                // Reload the page to show the updated location
                                window.location.reload();
                            } else {
                                alert('Error updating location: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Error updating location. Please try again.');
                        });
                    },
                    // Error callback
                    function(error) {
                        console.error('Error obtaining location:', error);
                        alert('Error obtaining your location. Please check your browser permissions.');
                    }
                );
            } else {
                alert('Geolocation is not supported by this browser.');
            }
        }
        @endif
    </script>
@endsection

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Order Details</h1>
                <div>
                    <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left"></i> Back to Orders
                    </a>
                    @if($order->canBeReviewed())
                        <a href="{{ route('reviews.create', ['order_id' => $order->id]) }}" class="btn btn-primary">
                            <i class="fas fa-star"></i> Write a Review
                        </a>
                    @endif
                </div>
            </div>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Order #{{ $order->order_number }}</h5>
                        <span class="badge 
                            @if($order->status === 'pending') bg-warning text-dark
                            @elseif($order->status === 'processing') bg-info text-dark
                            @elseif($order->status === 'completed') bg-success
                            @elseif($order->status === 'delivered') bg-success
                            @elseif($order->status === 'cancelled') bg-danger
                            @else bg-secondary
                            @endif
                        ">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Order Date:</strong></p>
                            <p>{{ $order->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Total Amount:</strong></p>
                            <p>₹{{ number_format($order->total_amount, 2) }}</p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Delivery Address:</strong></p>
                            <p>{{ $order->delivery_address }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Delivery Phone:</strong></p>
                            <p>{{ $order->delivery_phone }}</p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Delivery Time:</strong></p>
                            <p>{{ $order->delivery_time ? $order->delivery_time->format('M d, Y h:i A') : 'Not specified' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Payment Method:</strong></p>
                            <p>{{ ucfirst($order->payment_method) }} 
                                @if($order->is_paid) 
                                    <span class="badge bg-success">Paid</span>
                                @else
                                    <span class="badge bg-warning text-dark">Unpaid</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    @if($order->assigned_to)
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Assigned To:</strong></p>
                            <p>{{ $order->assignedProvider->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Order Type:</strong></p>
                            <p>{{ ucfirst($order->order_type) }}</p>
                        </div>
                    </div>
                    @endif
                    @if($order->cancellation_reason)
                    <div class="mt-3">
                        <p class="mb-1"><strong>Cancellation Reason:</strong></p>
                        <p>{{ $order->cancellation_reason }}</p>
                    </div>
                    @endif
                    @if($order->special_instructions)
                    <div class="mt-3">
                        <p class="mb-1"><strong>Special Instructions:</strong></p>
                        <p>{{ $order->special_instructions }}</p>
                    </div>
                    @endif
                    
                    <!-- Location Tracking Map -->
                    <div class="mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            @if(in_array($order->status, ['processing', 'completed', 'ready for delivery']) && ($order->latitude || $order->longitude))
                                <h6 class="mb-0"><i class="fas fa-map-marker-alt text-danger me-2"></i> Live Order Tracking</h6>
                                <div>
                                    <small class="text-muted">Last updated: <span id="location-updated-time">{{ $order->location_updated_at ? $order->location_updated_at->diffForHumans() : 'N/A' }}</span></small>
                                    
                                    @if(auth()->user()->hasRole('admin') || auth()->id() === $order->assigned_to)
                                    <button type="button" onclick="updateOrderLocation()" class="btn btn-sm btn-primary ms-2">
                                        <i class="fas fa-location-arrow"></i> Update Location
                                    </button>
                                    @endif
                                </div>
                            @elseif($order->delivery_latitude && $order->delivery_longitude)
                                <h6 class="mb-0"><i class="fas fa-map-marker-alt text-danger me-2"></i> Delivery Location</h6>
                                <div>
                                    <small class="text-muted">Coordinates selected during order placement</small>
                                </div>
                            @else
                                <h6 class="mb-0"><i class="fas fa-map-marker-alt text-danger me-2"></i> Order Location</h6>
                                <div>
                                    <small class="text-muted">Delivery address mapped to approximate location</small>
                                </div>
                            @endif
                        </div>
                        <div id="track-order" class="anchor-target"></div>
                        <div id="order-tracking-map" style="height: 350px; border-radius: 8px;"></div>
                    </div>
                </div>
                @if($order->status === 'pending')
                <div class="card-footer bg-white">
                    <form action="{{ route('orders.cancel', $order->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="cancellation_reason" class="form-label">Cancellation Reason:</label>
                            <textarea class="form-control" id="cancellation_reason" name="cancellation_reason" rows="2" required></textarea>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this order?')">
                                <i class="fas fa-ban"></i> Cancel Order
                            </button>
                        </div>
                    </form>
                </div>
                @endif
                
                @if($order->reviews->count() > 0)
                <div class="card-footer bg-white">
                    <h6 class="mb-3">Your Review</h6>
                    @foreach($order->reviews as $review)
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <span class="avatar-text bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-weight: bold;">
                                    {{ substr($review->user->name, 0, 1) }}
                                </span>
                            </div>
                            <div class="ms-3 flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div>
                                        <strong>{{ $review->user->name }}</strong>
                                        <small class="text-muted ms-2">{{ $review->created_at->format('M d, Y') }}</small>
                                    </div>
                                    <div>
                                        @if($review->is_approved)
                                            <span class="badge bg-success">Approved</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    {!! $review->getRatingStarsHtml() !!}
                                </div>
                                <p class="mb-1 mt-2">{{ $review->comment }}</p>
                                <div class="mt-2">
                                    <a href="{{ route('reviews.show', $review->id) }}" class="btn btn-sm btn-outline-primary">View Review</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @endif
            </div>
            
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Order Items</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Service</th>
                                    <th>Type</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        @if($item->service)
                                            <div class="d-flex align-items-center">
                                                @if($item->service_type === 'App\Models\LaundryService')
                                                    <span class="badge bg-primary me-2">Laundry</span>
                                                @elseif($item->service_type === 'App\Models\TiffinService')
                                                    <span class="badge bg-success me-2">Tiffin</span>
                                                @endif
                                                {{ $item->service->name }}
                                            </div>
                                        @else
                                            <span class="text-muted">Service not available</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->service_type === 'App\Models\LaundryService')
                                            {{ $item->service->service_type }}
                                        @elseif($item->service_type === 'App\Models\TiffinService')
                                            {{ $item->service->meal_type }}
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">₹{{ number_format($item->price, 2) }}</td>
                                    <td class="text-end">₹{{ number_format($item->quantity * $item->price, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-group-divider">
                                <tr>
                                    <th colspan="4" class="text-end">Total:</th>
                                    <th class="text-end">₹{{ number_format($order->total_amount, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection