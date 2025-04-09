@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Review Management</h1>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
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
            
            <!-- Pending Reviews -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0 d-flex align-items-center">
                        <i class="fas fa-clock text-warning me-2"></i> Pending Reviews
                        @if($pendingReviews->count() > 0)
                            <span class="badge bg-warning text-dark ms-2">{{ $pendingReviews->total() }}</span>
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    @if($pendingReviews->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Customer</th>
                                        <th>Service</th>
                                        <th>Rating</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingReviews as $review)
                                        <tr>
                                            <td>{{ $review->created_at->format('M d, Y') }}</td>
                                            <td>{{ $review->user->name }}</td>
                                            <td>
                                                @if($review->reviewable_type === 'App\Models\LaundryService')
                                                    <span class="badge bg-primary">Laundry</span>
                                                @elseif($review->reviewable_type === 'App\Models\TiffinService')
                                                    <span class="badge bg-success">Tiffin</span>
                                                @endif
                                                {{ $review->reviewable->name }}
                                            </td>
                                            <td>{!! $review->getRatingStarsHtml() !!}</td>
                                            <td class="text-end">
                                                <div class="btn-group">
                                                    <a href="{{ route('reviews.show', $review->id) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('reviews.edit', $review->id) }}" class="btn btn-sm btn-outline-secondary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-success">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('admin.reviews.reject', $review->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to reject and delete this review?')">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-4">
                            {{ $pendingReviews->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                            <h5>No Pending Reviews</h5>
                            <p class="text-muted">All reviews have been moderated. Great job!</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Approved Reviews -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0 d-flex align-items-center">
                        <i class="fas fa-check-circle text-success me-2"></i> Approved Reviews
                        @if($approvedReviews->count() > 0)
                            <span class="badge bg-success ms-2">{{ $approvedReviews->total() }}</span>
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    @if($approvedReviews->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Customer</th>
                                        <th>Service</th>
                                        <th>Rating</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($approvedReviews as $review)
                                        <tr>
                                            <td>{{ $review->created_at->format('M d, Y') }}</td>
                                            <td>{{ $review->user->name }}</td>
                                            <td>
                                                @if($review->reviewable_type === 'App\Models\LaundryService')
                                                    <span class="badge bg-primary">Laundry</span>
                                                @elseif($review->reviewable_type === 'App\Models\TiffinService')
                                                    <span class="badge bg-success">Tiffin</span>
                                                @endif
                                                {{ $review->reviewable->name }}
                                            </td>
                                            <td>{!! $review->getRatingStarsHtml() !!}</td>
                                            <td class="text-end">
                                                <div class="btn-group">
                                                    <a href="{{ route('reviews.show', $review->id) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('reviews.edit', $review->id) }}" class="btn btn-sm btn-outline-secondary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this review?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-4">
                            {{ $approvedReviews->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <img src="{{ asset('images/no-data.svg') }}" alt="No Reviews" class="img-fluid mb-3" style="max-height: 150px;">
                            <h5>No Approved Reviews Yet</h5>
                            <p class="text-muted">When you approve reviews, they will appear here.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection