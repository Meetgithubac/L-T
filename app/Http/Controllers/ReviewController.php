<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Order;
use App\Models\LaundryService;
use App\Models\TiffinService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    /**
     * Display a paginated listing of reviews, with optional filters.
     */
    public function index(Request $request)
    {
        $reviews = Review::with(['user', 'reviewable', 'order'])
            ->latest();
        
        // Filter by status if specified
        if ($request->has('status')) {
            if ($request->status === 'approved') {
                $reviews->approved();
            } elseif ($request->status === 'pending') {
                $reviews->pending();
            }
        }
        
        // Filter by rating if specified
        if ($request->filled('rating')) {
            $reviews->where('rating', $request->rating);
        }
        
        // Filter by service type if specified
        if ($request->filled('service_type')) {
            $reviews->ofServiceType($request->service_type);
        }
        
        // If user is not an admin, only show approved reviews and their own reviews
        if (!auth()->user()->hasRole('admin')) {
            $reviews->where(function($query) {
                $query->where('is_approved', true)
                      ->orWhere('user_id', auth()->id());
            });
        }
        
        $reviews = $reviews->paginate(10)
            ->withQueryString();
        
        return view('reviews.index', compact('reviews'));
    }

    /**
     * Show the form for creating a new review.
     */
    public function create(Request $request)
    {
        // If order_id is provided, get the order to allow reviewing its services
        if ($request->filled('order_id')) {
            $order = Order::findOrFail($request->order_id);
            
            // Check if user owns the order
            if ($order->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
                return redirect()->route('orders.index')
                    ->with('error', 'You can only review your own orders.');
            }
            
            // Check if order is completed
            if ($order->status !== 'completed') {
                return redirect()->route('orders.show', $order)
                    ->with('error', 'You can only review completed orders.');
            }
            
            return view('reviews.create', compact('order'));
        }
        
        // If service_id and type are provided, get the specific service
        if ($request->filled('laundry_id')) {
            $service = LaundryService::findOrFail($request->laundry_id);
            $service_type = 'laundry_id';
            return view('reviews.create', compact('service', 'service_type'));
        }
        
        if ($request->filled('tiffin_id')) {
            $service = TiffinService::findOrFail($request->tiffin_id);
            $service_type = 'tiffin_id';
            return view('reviews.create', compact('service', 'service_type'));
        }
        
        // If no specific order or service, show all available services to review
        $laundryServices = LaundryService::where('is_active', true)->get();
        $tiffinServices = TiffinService::where('is_active', true)->get();
        
        return view('reviews.create', compact('laundryServices', 'tiffinServices'));
    }

    /**
     * Store a newly created review in the database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:5|max:500',
            'service' => 'sometimes|required|string',
            'order_id' => 'sometimes|exists:orders,id',
            'laundry_id' => 'sometimes|exists:laundry_services,id',
            'tiffin_id' => 'sometimes|exists:tiffin_services,id',
        ]);
        
        // Start a database transaction
        DB::beginTransaction();
        
        try {
            $review = new Review();
            $review->user_id = auth()->id();
            $review->rating = $request->rating;
            $review->comment = $request->comment;
            $review->is_approved = auth()->user()->hasRole('admin') ? true : false;
            
            // Set order_id if provided
            if ($request->filled('order_id')) {
                $review->order_id = $request->order_id;
            }
            
            // Determine which service to associate with the review
            if ($request->filled('service')) {
                [$type, $id] = explode('_', $request->service);
                
                if ($type === 'laundry') {
                    $service = LaundryService::findOrFail($id);
                    $review->reviewable()->associate($service);
                } elseif ($type === 'tiffin') {
                    $service = TiffinService::findOrFail($id);
                    $review->reviewable()->associate($service);
                }
            } elseif ($request->filled('laundry_id')) {
                $service = LaundryService::findOrFail($request->laundry_id);
                $review->reviewable()->associate($service);
            } elseif ($request->filled('tiffin_id')) {
                $service = TiffinService::findOrFail($request->tiffin_id);
                $review->reviewable()->associate($service);
            } else {
                return redirect()->back()->with('error', 'Please select a service to review.')->withInput();
            }
            
            $review->save();
            
            // Update the average rating of the service
            $this->updateServiceRating($review->reviewable);
            
            DB::commit();
            
            return redirect()->route('reviews.show', $review)
                ->with('success', 'Your review has been submitted and will be visible after approval.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred while saving your review: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified review.
     */
    public function show(Review $review)
    {
        // Check if user can view this review
        if (!$review->is_approved && $review->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            return redirect()->route('reviews.index')
                ->with('error', 'This review is pending approval and cannot be viewed.');
        }
        
        return view('reviews.show', compact('review'));
    }

    /**
     * Show the form for editing the specified review.
     */
    public function edit(Review $review)
    {
        // Check if user can edit this review
        if ($review->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            return redirect()->route('reviews.index')
                ->with('error', 'You can only edit your own reviews.');
        }
        
        return view('reviews.edit', compact('review'));
    }

    /**
     * Update the specified review in storage.
     */
    public function update(Request $request, Review $review)
    {
        // Check if user can update this review
        if ($review->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            return redirect()->route('reviews.index')
                ->with('error', 'You can only edit your own reviews.');
        }
        
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:5|max:500',
            'is_approved' => 'sometimes|boolean',
        ]);
        
        // Start a database transaction
        DB::beginTransaction();
        
        try {
            $review->rating = $request->rating;
            $review->comment = $request->comment;
            
            // Only admins can change approval status
            if (auth()->user()->hasRole('admin') && $request->has('is_approved')) {
                $review->is_approved = $request->has('is_approved');
            }
            
            $review->save();
            
            // Update the average rating of the service
            $this->updateServiceRating($review->reviewable);
            
            DB::commit();
            
            return redirect()->route('reviews.show', $review)
                ->with('success', 'Review updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred while updating your review: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified review from storage.
     */
    public function destroy(Review $review)
    {
        // Check if user can delete this review
        if ($review->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            return redirect()->route('reviews.index')
                ->with('error', 'You can only delete your own reviews.');
        }
        
        // Start a database transaction
        DB::beginTransaction();
        
        try {
            $service = $review->reviewable;
            $review->delete();
            
            // Update the average rating of the service
            $this->updateServiceRating($service);
            
            DB::commit();
            
            return redirect()->route('reviews.index')
                ->with('success', 'Review deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred while deleting your review: ' . $e->getMessage());
        }
    }

    /**
     * Approve a review (admin only)
     */
    public function approve(Review $review)
    {
        // Check if user is an admin
        if (!auth()->user()->hasRole('admin')) {
            return redirect()->route('reviews.index')
                ->with('error', 'You do not have permission to approve reviews.');
        }
        
        // Start a database transaction
        DB::beginTransaction();
        
        try {
            $review->is_approved = true;
            $review->save();
            
            // Update the average rating of the service
            $this->updateServiceRating($review->reviewable);
            
            DB::commit();
            
            return redirect()->back()
                ->with('success', 'Review approved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred while approving the review: ' . $e->getMessage());
        }
    }
    
    /**
     * Update the average rating of a service based on its approved reviews
     */
    private function updateServiceRating($service)
    {
        if (!$service) {
            return;
        }
        
        $averageRating = Review::where('reviewable_type', get_class($service))
            ->where('reviewable_id', $service->id)
            ->where('is_approved', true)
            ->avg('rating');
        
        $service->average_rating = $averageRating ?? 0;
        $service->save();
    }
}