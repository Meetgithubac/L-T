@extends('layouts.app')

@section('title', 'All Reviews')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h1 class="h2 mb-0">Reviews</h1>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Home
                </a>
            </div>
            <p class="text-muted">Manage and view all reviews</p>
        </div>
    </div>
    
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0">All Reviews</h5>
                </div>
                <div class="col-md-6">
                    <form action="{{ route('reviews.index') }}" method="GET" class="d-flex">
                        <select name="status" class="form-select me-2" onchange="this.form.submit()">
                            <option value="">All Status</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                        <select name="rating" class="form-select me-2" onchange="this.form.submit()">
                            <option value="">All Ratings</option>
                            <option value="5" {{ request('rating') == 5 ? 'selected' : '' }}>5 Stars</option>
                            <option value="4" {{ request('rating') == 4 ? 'selected' : '' }}>4 Stars</option>
                            <option value="3" {{ request('rating') == 3 ? 'selected' : '' }}>3 Stars</option>
                            <option value="2" {{ request('rating') == 2 ? 'selected' : '' }}>2 Stars</option>
                            <option value="1" {{ request('rating') == 1 ? 'selected' : '' }}>1 Star</option>
                        </select>
                        <select name="service_type" class="form-select me-2" onchange="this.form.submit()">
                            <option value="">All Services</option>
                            <option value="laundry" {{ request('service_type') === 'laundry' ? 'selected' : '' }}>Laundry</option>
                            <option value="tiffin" {{ request('service_type') === 'tiffin' ? 'selected' : '' }}>Tiffin</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            @if($reviews->count() > 0)
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>User</th>
                                <th>Service</th>
                                <th>Rating</th>
                                <th>Comment</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reviews as $review)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="avatar-text bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 14px;">
                                                {{ substr($review->user->name, 0, 1) }}
                                            </span>
                                            <div>{{ $review->user->name }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($review->reviewable_type === 'App\Models\LaundryService')
                                            <span class="badge bg-primary">Laundry</span>
                                            <div>{{ $review->reviewable->name ?? 'Unknown Service' }}</div>
                                        @elseif($review->reviewable_type === 'App\Models\TiffinService')
                                            <span class="badge bg-success">Tiffin</span>
                                            <div>{{ $review->reviewable->name ?? 'Unknown Service' }}</div>
                                        @else
                                            <span class="badge bg-secondary">Unknown</span>
                                        @endif
                                    </td>
                                    <td>
                                        {!! $review->getRatingStarsHtml() !!}
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 200px;">{{ $review->comment }}</div>
                                    </td>
                                    <td>
                                        @if($review->is_approved)
                                            <span class="badge bg-success">Approved</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @endif
                                    </td>
                                    <td>{{ $review->created_at->format('M d, Y') }}</td>
                                    <td class="text-nowrap">
                                        <a href="{{ route('reviews.show', $review) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @if(auth()->user()->hasRole('admin') || auth()->id() === $review->user_id)
                                            <a href="{{ route('reviews.edit', $review) }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this review?')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        @if(auth()->user()->hasRole('admin') && !$review->is_approved)
                                            <form action="{{ route('reviews.approve', $review) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="px-4 py-3">
                    {{ $reviews->withQueryString()->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-star text-muted fa-3x"></i>
                    </div>
                    <h4>No Reviews Found</h4>
                    <p class="text-muted">There are no reviews matching your criteria.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection