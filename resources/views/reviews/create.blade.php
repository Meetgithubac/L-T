@extends('layouts.app')

@section('title', 'Write a Review')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    @if(isset($order))
                    <li class="breadcrumb-item"><a href="{{ route('orders.show', $order) }}">Order #{{ $order->order_number }}</a></li>
                    @endif
                    <li class="breadcrumb-item active">Write a Review</li>
                </ol>
            </nav>
            
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h1 class="h4 mb-0">Write a Review</h1>
                </div>
                <div class="card-body">
                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    
                    @if(isset($order) && $order->items->count() > 0)
                        <div class="mb-4">
                            <h5 class="card-title mb-3">Order #{{ $order->order_number }}</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Service</th>
                                            <th>Type</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
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
                                            <td>₹{{ number_format($item->price, 2) }}</td>
                                            <td>{{ $item->quantity }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                    
                    <form action="{{ route('reviews.store') }}" method="POST">
                        @csrf
                        
                        @if(isset($order))
                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                        @endif
                        
                        @if(isset($service))
                            <input type="hidden" name="{{ $service_type }}_id" value="{{ $service->id }}">
                        @else
                            <div class="mb-3">
                                <label for="service" class="form-label">Select Service to Review</label>
                                <select name="service" id="service" class="form-select @error('service') is-invalid @enderror" required>
                                    <option value="">Select a service...</option>
                                    @if(isset($order))
                                        @foreach($order->items as $item)
                                            @if($item->service)
                                                <option value="{{ strtolower(class_basename($item->service_type)) }}_{{ $item->service->id }}">
                                                    {{ $item->service->name }} ({{ class_basename($item->service_type) }})
                                                </option>
                                            @endif
                                        @endforeach
                                    @else
                                        @foreach($laundryServices as $service)
                                            <option value="laundry_{{ $service->id }}">{{ $service->name }} (Laundry)</option>
                                        @endforeach
                                        @foreach($tiffinServices as $service)
                                            <option value="tiffin_{{ $service->id }}">{{ $service->name }} (Tiffin)</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('service')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                        
                        <div class="mb-4">
                            <label class="form-label">Rating</label>
                            <div class="rating-input d-flex flex-wrap gap-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="rating" id="rating5" value="5" {{ old('rating') == 5 ? 'checked' : '' }} required>
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
                                    <input class="form-check-input" type="radio" name="rating" id="rating4" value="4" {{ old('rating') == 4 ? 'checked' : '' }}>
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
                                    <input class="form-check-input" type="radio" name="rating" id="rating3" value="3" {{ old('rating') == 3 ? 'checked' : '' }}>
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
                                    <input class="form-check-input" type="radio" name="rating" id="rating2" value="2" {{ old('rating') == 2 ? 'checked' : '' }}>
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
                                    <input class="form-check-input" type="radio" name="rating" id="rating1" value="1" {{ old('rating') == 1 ? 'checked' : '' }}>
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
                            <textarea class="form-control @error('comment') is-invalid @enderror" id="comment" name="comment" rows="5" placeholder="Share your experience with this service..." required>{{ old('comment') }}</textarea>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-muted">Your review will be visible to other customers after approval.</div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ isset($order) ? route('orders.show', $order) : route('reviews.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-1"></i> Submit Review
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection