@extends('layouts.app')

@section('title', 'Laundry Services')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2 mb-0">Laundry Services</h1>
            <p class="text-muted">Browse our range of professional laundry services.</p>
        </div>
        <div class="col-md-4 text-md-end">
            @can('create laundry services')
            <a href="{{ route('laundry.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Add Service
            </a>
            @endcan
        </div>
    </div>
    
    <!-- Filter & Search -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('laundry.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="service_type" class="form-label">Service Type</label>
                    <select class="form-select" id="service_type" name="service_type">
                        <option value="">All Types</option>
                        <option value="wash" {{ request('service_type') == 'wash' ? 'selected' : '' }}>Wash</option>
                        <option value="dry_clean" {{ request('service_type') == 'dry_clean' ? 'selected' : '' }}>Dry Clean</option>
                        <option value="iron" {{ request('service_type') == 'iron' ? 'selected' : '' }}>Iron</option>
                        <option value="fold" {{ request('service_type') == 'fold' ? 'selected' : '' }}>Fold</option>
                        <option value="package_deal" {{ request('service_type') == 'package_deal' ? 'selected' : '' }}>Package Deal</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="sort" class="form-label">Sort By</label>
                    <select class="form-select" id="sort" name="sort">
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name (A-Z)</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
                        <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Price (Low to High)</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price (High to Low)</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="search" class="form-label">Search</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="search" name="search" placeholder="Search services..." value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Services Grid -->
    <div class="row">
        @forelse($laundryServices as $service)
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm service-card">
                @if($service->image)
                <img src="{{ $service->image }}" class="card-img-top" alt="{{ $service->name }}">
                @else
                <div class="bg-light text-center py-5">
                    <i class="fas fa-tshirt fa-4x text-secondary"></i>
                </div>
                @endif
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="card-title mb-0">{{ $service->name }}</h5>
                        <span class="badge bg-{{ $service->is_available ? 'success' : 'danger' }}">
                            {{ $service->is_available ? 'Available' : 'Unavailable' }}
                        </span>
                    </div>
                    <p class="card-text text-muted small mb-3">{{ Str::limit($service->description, 100) }}</p>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <span class="fw-bold text-primary">₹{{ $service->price }}</span>
                            <small class="text-muted">/ {{ $service->unit }}</small>
                        </div>
                        <div>
                            <span class="badge bg-info text-dark">{{ ucfirst($service->service_type) }}</span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i> {{ $service->estimated_hours }} hours
                        </small>
                        @if($service->provider)
                        <small class="text-muted">
                            <i class="fas fa-user me-1"></i> {{ $service->provider->name }}
                        </small>
                        @endif
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top-0 d-flex justify-content-between">
                    <a href="{{ route('laundry.show', $service) }}" class="btn btn-outline-primary">View Details</a>
                    @can('edit laundry services')
                        @if($service->provider_id == Auth::id() || Auth::user()->hasRole('admin'))
                        <a href="{{ route('laundry.edit', $service) }}" class="btn btn-outline-secondary">
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
                    <i class="fas fa-tshirt fa-3x mb-3"></i>
                    <h3 class="h4">No laundry services found</h3>
                    <p class="mb-0">There are currently no laundry services available.</p>
                    @can('create laundry services')
                    <div class="mt-3">
                        <a href="{{ route('laundry.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Add Laundry Service
                        </a>
                    </div>
                    @endcan
                </div>
            </div>
        </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    @if($laundryServices->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $laundryServices->links() }}
    </div>
    @endif
    
    <!-- Services Information -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h2 class="h4 mb-4">About Our Laundry Services</h2>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="p-3 bg-primary bg-opacity-10 rounded-3">
                                        <i class="fas fa-tshirt fa-2x text-primary"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <h3 class="h5">Professional Care</h3>
                                    <p class="text-muted mb-0">Our experienced team ensures each garment receives the appropriate care tailored to its fabric type and condition.</p>
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
                                    <h3 class="h5">Eco-Friendly</h3>
                                    <p class="text-muted mb-0">We use environmentally friendly detergents and efficient machines to reduce our environmental impact.</p>
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
                                    <h3 class="h5">Door-to-Door Service</h3>
                                    <p class="text-muted mb-0">Enjoy convenient pickup and delivery services at your preferred time and location.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="p-3 bg-warning bg-opacity-10 rounded-3">
                                        <i class="fas fa-clock fa-2x text-warning"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <h3 class="h5">Quick Turnaround</h3>
                                    <p class="text-muted mb-0">We offer express services for when you need your clothes back in a hurry, without compromising on quality.</p>
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