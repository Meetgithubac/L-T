@extends('layouts.app')

@section('title', 'Tiffin Services')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2 mb-0">Tiffin Services</h1>
            <p class="text-muted">Browse our delicious and nutritious meal services.</p>
        </div>
        <div class="col-md-4 text-md-end">
            @can('create tiffin services')
            <a href="{{ route('tiffin.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Add Service
            </a>
            @endcan
        </div>
    </div>
    
    <!-- Filter & Search -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('tiffin.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="meal_type" class="form-label">Meal Type</label>
                    <select class="form-select" id="meal_type" name="meal_type">
                        <option value="">All Types</option>
                        <option value="breakfast" {{ request('meal_type') == 'breakfast' ? 'selected' : '' }}>Breakfast</option>
                        <option value="lunch" {{ request('meal_type') == 'lunch' ? 'selected' : '' }}>Lunch</option>
                        <option value="dinner" {{ request('meal_type') == 'dinner' ? 'selected' : '' }}>Dinner</option>
                        <option value="combo" {{ request('meal_type') == 'combo' ? 'selected' : '' }}>Combo Meal</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="cuisine" class="form-label">Cuisine</label>
                    <select class="form-select" id="cuisine" name="cuisine">
                        <option value="">All Cuisines</option>
                        <option value="indian" {{ request('cuisine') == 'indian' ? 'selected' : '' }}>Indian</option>
                        <option value="chinese" {{ request('cuisine') == 'chinese' ? 'selected' : '' }}>Chinese</option>
                        <option value="italian" {{ request('cuisine') == 'italian' ? 'selected' : '' }}>Italian</option>
                        <option value="thai" {{ request('cuisine') == 'thai' ? 'selected' : '' }}>Thai</option>
                        <option value="mexican" {{ request('cuisine') == 'mexican' ? 'selected' : '' }}>Mexican</option>
                        <option value="continental" {{ request('cuisine') == 'continental' ? 'selected' : '' }}>Continental</option>
                        <option value="mixed" {{ request('cuisine') == 'mixed' ? 'selected' : '' }}>Mixed</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="sort" class="form-label">Sort By</label>
                    <select class="form-select" id="sort" name="sort">
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name (A-Z)</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
                        <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Price (Low to High)</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price (High to Low)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="search" class="form-label">Search</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="search" name="search" placeholder="Search services..." value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="is_vegetarian" name="is_vegetarian" value="1" {{ request('is_vegetarian') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_vegetarian">Vegetarian Only</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="is_available" name="is_available" value="1" {{ request('is_available', '1') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_available">Available Only</label>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Services Grid -->
    <div class="row">
        @forelse($tiffinServices as $service)
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm service-card">
                @if($service->image)
                <img src="{{ $service->image }}" class="card-img-top" alt="{{ $service->name }}">
                @else
                <div class="bg-light text-center py-5">
                    <i class="fas fa-utensils fa-4x text-secondary"></i>
                </div>
                @endif
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="card-title mb-0">{{ $service->name }}</h5>
                        <div>
                            <span class="badge bg-{{ $service->is_available ? 'success' : 'danger' }} me-1">
                                {{ $service->is_available ? 'Available' : 'Unavailable' }}
                            </span>
                            @if($service->is_vegetarian)
                            <span class="badge bg-success">
                                <i class="fas fa-leaf me-1"></i> Veg
                            </span>
                            @else
                            <span class="badge bg-danger">
                                <i class="fas fa-drumstick-bite me-1"></i> Non-Veg
                            </span>
                            @endif
                        </div>
                    </div>
                    <p class="card-text text-muted small mb-3">{{ Str::limit($service->description, 100) }}</p>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <span class="fw-bold text-primary">₹{{ $service->price }}</span>
                        </div>
                        <div>
                            <span class="badge bg-info text-dark">{{ ucfirst($service->meal_type) }}</span>
                            <span class="badge bg-secondary">{{ ucfirst($service->cuisine) }}</span>
                        </div>
                    </div>
                    <div class="small text-muted mb-2">
                        <strong>Menu Items:</strong>
                    </div>
                    <div class="small text-muted">
                        @if(is_array($service->menu_items) && count($service->menu_items) > 0)
                            <ul class="mb-0 ps-3">
                                @foreach(array_slice($service->menu_items, 0, 3) as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                                @if(count($service->menu_items) > 3)
                                    <li>+ {{ count($service->menu_items) - 3 }} more items</li>
                                @endif
                            </ul>
                        @else
                            <p class="mb-0">Menu information not available</p>
                        @endif
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top-0 d-flex justify-content-between">
                    <a href="{{ route('tiffin.show', $service) }}" class="btn btn-outline-primary">View Details</a>
                    @can('edit tiffin services')
                        @if($service->provider_id == Auth::id() || Auth::user()->hasRole('admin'))
                        <a href="{{ route('tiffin.edit', $service) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-edit"></i>
                        </a>
                        @endif
                    @endcan
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info">
                <div class="text-center py-4">
                    <i class="fas fa-utensils fa-3x mb-3"></i>
                    <h3 class="h4">No tiffin services found</h3>
                    <p class="mb-0">There are currently no tiffin services available.</p>
                    @can('create tiffin services')
                    <div class="mt-3">
                        <a href="{{ route('tiffin.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Add Tiffin Service
                        </a>
                    </div>
                    @endcan
                </div>
            </div>
        </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    @if($tiffinServices->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $tiffinServices->links() }}
    </div>
    @endif
    
    <!-- Services Information -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h2 class="h4 mb-4">About Our Tiffin Services</h2>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="p-3 bg-primary bg-opacity-10 rounded-3">
                                        <i class="fas fa-utensils fa-2x text-primary"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <h3 class="h5">Fresh Ingredients</h3>
                                    <p class="text-muted mb-0">We use only the freshest ingredients, sourced locally whenever possible, to prepare nutritious and delicious meals.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="p-3 bg-success bg-opacity-10 rounded-3">
                                        <i class="fas fa-leaf fa-2x text-success"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <h3 class="h5">Healthy Options</h3>
                                    <p class="text-muted mb-0">From vegetarian to specialized diet plans, we offer a variety of options to cater to your dietary preferences and needs.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4 mb-md-0">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="p-3 bg-info bg-opacity-10 rounded-3">
                                        <i class="fas fa-truck fa-2x text-info"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <h3 class="h5">Timely Delivery</h3>
                                    <p class="text-muted mb-0">Our efficient delivery system ensures that your meals arrive on time, hot and ready to eat.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="p-3 bg-warning bg-opacity-10 rounded-3">
                                        <i class="fas fa-calendar-alt fa-2x text-warning"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <h3 class="h5">Flexible Plans</h3>
                                    <p class="text-muted mb-0">From one-time orders to weekly subscriptions, choose the plan that best fits your lifestyle and preferences.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection