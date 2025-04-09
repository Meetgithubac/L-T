@extends('layouts.app')

@section('title', 'Review Details')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('reviews.index') }}">Reviews</a></li>
                    <li class="breadcrumb-item active">Review Details</li>
                </ol>
            </nav>
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h2">Review Details</h1>
                <div>
                    <a href="{{ route('reviews.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left me-1"></i> Back to Reviews
                    </a>
                    @if(auth()->user()->hasRole('admin') || auth()->id() === $review->user_id)
                    <a href="{{ route('reviews.edit', $review) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-1"></i> Edit Review
                    </a>
                    @endif
                </div>
            </div>
            
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="card-title mb-0">Review #{{ $review->id }}</h5>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <span class="badge {{ $review->is_approved ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ $review->is_approved ? 'Approved' : 'Pending Approval' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <h6 class="text-muted mb-2">Reviewer</h6>
                            <div class="d-flex align-items-center">
                                <span class="avatar-text bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-weight: bold;">
                                    {{ substr($review->user->name, 0, 1) }}
                                </span>
                                <div class="ms-2">
                                    <div class="fw-bold">{{ $review->user->name }}</div>
                                    <div class="small text-muted">{{ $review->user->email }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Service</h6>
                            <div>
                                @if($review->reviewable_type === 'App\Models\LaundryService')
                                    <span class="badge bg-primary">Laundry Service</span>
                                @elseif($review->reviewable_type === 'App\Models\TiffinService')
                                    <span class="badge bg-success">Tiffin Service</span>
                                @endif
                                
                                @if($review->reviewable)
                                <div class="mt-1">
                                    <a href="{{ 
                                        $review->reviewable_type === 'App\Models\LaundryService' 
                                            ? route('laundry.show', $review->reviewable) 
                                            : route('tiffin.show', $review->reviewable) 
                                    }}" class="fw-bold text-decoration-none">
                                        {{ $review->reviewable->name }}
                                    </a>
                                </div>
                                @else
                                <div class="mt-1 text-muted">Service no longer available</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Rating</h6>
                        <div class="mb-1">
                            {!! $review->getRatingStarsHtml() !!}
                            <span class="ms-2 fw-bold">{{ $review->rating }}/5</span>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Comment</h6>
                        <div class="p-3 bg-light rounded">
                            {{ $review->comment }}
                        </div>
                    </div>
                    
                    @if($review->order)
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Related Order</h6>
                        <div>
                            <a href="{{ route('orders.show', $review->order) }}" class="text-decoration-none">
                                Order #{{ $review->order->order_number }}
                            </a>
                            <span class="text-muted ms-2">
                                ({{ $review->order->created_at->format('M d, Y') }})
                            </span>
                        </div>
                    </div>
                    @endif
                    
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <h6 class="text-muted mb-2">Created</h6>
                            <div>{{ $review->created_at->format('M d, Y h:i A') }}</div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Last Updated</h6>
                            <div>{{ $review->updated_at->format('M d, Y h:i A') }}</div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between">
                        @if(auth()->user()->hasRole('admin') || auth()->id() === $review->user_id)
                        <div>
                            <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this review?')">
                                    <i class="fas fa-trash-alt me-1"></i> Delete Review
                                </button>
                            </form>
                        </div>
                        @endif
                        
                        @if(auth()->user()->hasRole('admin') && !$review->is_approved)
                        <div>
                            <form action="{{ route('reviews.approve', $review) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check me-1"></i> Approve Review
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection