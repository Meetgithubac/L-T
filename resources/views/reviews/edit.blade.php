@extends('layouts.app')

@section('title', 'Edit Review')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('reviews.index') }}">Reviews</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('reviews.show', $review) }}">Review #{{ $review->id }}</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
            
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h1 class="h4 mb-0">Edit Review</h1>
                </div>
                <div class="card-body">
                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    
                    <div class="mb-4">
                        <h5 class="card-title mb-3">Review for</h5>
                        <div class="d-flex align-items-center">
                            @if($review->reviewable_type === 'App\Models\LaundryService')
                                <span class="badge bg-primary me-2">Laundry Service</span>
                            @elseif($review->reviewable_type === 'App\Models\TiffinService')
                                <span class="badge bg-success me-2">Tiffin Service</span>
                            @endif
                            
                            @if($review->reviewable)
                                <a href="{{ 
                                    $review->reviewable_type === 'App\Models\LaundryService' 
                                        ? route('laundry.show', $review->reviewable) 
                                        : route('tiffin.show', $review->reviewable) 
                                }}" class="fw-bold text-decoration-none">
                                    {{ $review->reviewable->name }}
                                </a>
                            @else
                                <span class="text-muted">Service no longer available</span>
                            @endif
                        </div>
                        
                        @if($review->order)
                        <div class="mt-2">
                            <span class="text-muted">From Order:</span>
                            <a href="{{ route('orders.show', $review->order) }}" class="text-decoration-none">
                                #{{ $review->order->order_number }}
                            </a>
                        </div>
                        @endif
                    </div>
                    
                    <form action="{{ route('reviews.update', $review) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label class="form-label">Rating</label>
                            <div class="rating-input d-flex flex-wrap gap-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="rating" id="rating5" value="5" {{ (old('rating', $review->rating) == 5) ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="rating5">
                                        <div class="text-warning">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <div class="small">Excellent</div>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="rating" id="rating4" value="4" {{ (old('rating', $review->rating) == 4) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="rating4">
                                        <div class="text-warning">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                        <div class="small">Good</div>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="rating" id="rating3" value="3" {{ (old('rating', $review->rating) == 3) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="rating3">
                                        <div class="text-warning">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                        <div class="small">Average</div>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="rating" id="rating2" value="2" {{ (old('rating', $review->rating) == 2) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="rating2">
                                        <div class="text-warning">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                        <div class="small">Below Average</div>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="rating" id="rating1" value="1" {{ (old('rating', $review->rating) == 1) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="rating1">
                                        <div class="text-warning">
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                        <div class="small">Poor</div>
                                    </label>
                                </div>
                            </div>
                            @error('rating')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="comment" class="form-label">Your Review</label>
                            <textarea class="form-control @error('comment') is-invalid @enderror" id="comment" name="comment" rows="5" required>{{ old('comment', $review->comment) }}</textarea>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        @if(auth()->user()->hasRole('admin'))
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_approved" name="is_approved" value="1" {{ old('is_approved', $review->is_approved) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_approved">
                                    Approve this review for public display
                                </label>
                            </div>
                            <div class="form-text text-muted">Only approved reviews will be visible to other customers.</div>
                        </div>
                        @endif
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('reviews.show', $review) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Update Review
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection