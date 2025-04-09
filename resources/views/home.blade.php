@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
<div class="hero-section bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
                <h1 class="display-4 fw-bold mb-3">Welcome to L&T Services</h1>
                <p class="fs-5 mb-4">Your one-stop solution for laundry and tiffin services. We bring convenience to your doorstep with high-quality and reliable services.</p>
                <div class="d-flex gap-3">
                    <a href="{{ route('laundry.index') }}" class="btn btn-light btn-lg">Explore Laundry</a>
                    <a href="{{ route('tiffin.index') }}" class="btn btn-outline-light btn-lg">Explore Tiffin</a>
                </div>
            </div>
            <div class="col-md-6">
                <img src="https://via.placeholder.com/600x400" alt="L&T Services" class="img-fluid rounded-3 shadow">
            </div>
        </div>
    </div>
</div>

<div class="py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-md-8 mx-auto">
                <h2 class="mb-3">Our Services</h2>
                <p class="text-muted">We offer a range of services to meet your daily needs, from fresh home-cooked meals to professional laundry services.</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card service-card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="fas fa-tshirt fa-3x text-primary"></i>
                        </div>
                        <h3 class="card-title h4">Laundry Services</h3>
                        <p class="card-text text-muted">Our laundry service takes care of your clothes with the utmost care, ensuring they come back clean, fresh, and well-maintained.</p>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Wash & Fold</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Dry Cleaning</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Ironing</li>
                            <li><i class="fas fa-check text-success me-2"></i>Express Service</li>
                        </ul>
                        <a href="{{ route('laundry.index') }}" class="btn btn-outline-primary">View Laundry Services</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card service-card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="fas fa-utensils fa-3x text-primary"></i>
                        </div>
                        <h3 class="card-title h4">Tiffin Services</h3>
                        <p class="card-text text-muted">Our tiffin service delivers healthy, hygienic, and delicious home-cooked meals right to your doorstep, saving you time and effort.</p>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Breakfast</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Lunch</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Dinner</li>
                            <li><i class="fas fa-check text-success me-2"></i>Special Diet Options</li>
                        </ul>
                        <a href="{{ route('tiffin.index') }}" class="btn btn-outline-primary">View Tiffin Services</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-light py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-md-8 mx-auto">
                <h2 class="mb-3">Featured Services</h2>
                <p class="text-muted">Check out some of our most popular services that our customers love.</p>
            </div>
        </div>

        <div class="row">
            <!-- Featured Laundry Services -->
            <div class="col-md-6 mb-4">
                <h3 class="h4 mb-4">Featured Laundry Services</h3>
                <div class="row">
                    @forelse($featuredLaundryServices as $service)
                        <div class="col-md-6 mb-4">
                            <div class="card service-card h-100 border-0 shadow-sm">
                                <div class="card-body p-3">
                                    <h4 class="card-title h5">{{ $service->name }}</h4>
                                    <p class="card-text small text-muted">{{ Str::limit($service->description, 80) }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold text-primary">₹{{ $service->price }} / {{ $service->unit }}</span>
                                        <a href="{{ route('laundry.show', $service) }}" class="btn btn-sm btn-outline-primary">View</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info">
                                No featured laundry services available at the moment.
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Featured Tiffin Services -->
            <div class="col-md-6 mb-4">
                <h3 class="h4 mb-4">Featured Tiffin Services</h3>
                <div class="row">
                    @forelse($featuredTiffinServices as $service)
                        <div class="col-md-6 mb-4">
                            <div class="card service-card h-100 border-0 shadow-sm">
                                <div class="card-body p-3">
                                    <h4 class="card-title h5">{{ $service->name }}</h4>
                                    <p class="card-text small text-muted">{{ Str::limit($service->description, 80) }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold text-primary">₹{{ $service->price }}</span>
                                        <a href="{{ route('tiffin.show', $service) }}" class="btn btn-sm btn-outline-primary">View</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info">
                                No featured tiffin services available at the moment.
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<div class="py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-md-8 mx-auto">
                <h2 class="mb-3">Why Choose Us</h2>
                <p class="text-muted">We pride ourselves on providing the best quality services with utmost convenience and reliability.</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fas fa-clock fa-3x text-primary"></i>
                    </div>
                    <h3 class="h5">Quick Turnaround</h3>
                    <p class="text-muted">We value your time and ensure quick delivery of your orders.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fas fa-star fa-3x text-primary"></i>
                    </div>
                    <h3 class="h5">Quality Service</h3>
                    <p class="text-muted">We prioritize quality in every aspect of our service delivery.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fas fa-truck fa-3x text-primary"></i>
                    </div>
                    <h3 class="h5">Doorstep Delivery</h3>
                    <p class="text-muted">We offer convenient doorstep pickup and delivery services.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8 mb-4 mb-md-0">
                <h2 class="mb-3">Ready to Experience Our Services?</h2>
                <p class="fs-5 mb-0">Sign up today and get your first order at a special discount!</p>
            </div>
            <div class="col-md-4 text-md-end">
                @guest
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg">Sign Up Now</a>
                @else
                    <a href="{{ route('orders.create') }}" class="btn btn-light btn-lg">Place an Order</a>
                @endguest
            </div>
        </div>
    </div>
</div>
@endsection