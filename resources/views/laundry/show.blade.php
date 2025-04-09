@extends('layouts.app')

@section('title', $laundryService->name)

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('laundry.index') }}">Laundry Services</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $laundryService->name }}</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <div class="row">
        <!-- Service Image -->
        <div class="col-md-5 mb-4 mb-md-0">
            <div class="card border-0 shadow-sm">
                @if($laundryService->image)
                <img src="{{ $laundryService->image }}" class="card-img-top img-fluid" alt="{{ $laundryService->name }}">
                @else
                <div class="bg-light text-center py-5">
                    <i class="fas fa-tshirt fa-6x text-secondary"></i>
                </div>
                @endif
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="badge bg-{{ $laundryService->is_available ? 'success' : 'danger' }}">
                            {{ $laundryService->is_available ? 'Available' : 'Unavailable' }}
                        </span>
                        <span class="badge bg-primary">{{ ucfirst(str_replace('_', ' ', $laundryService->service_type)) }}</span>
                        <span class="badge bg-info text-dark">{{ ucfirst(str_replace('_', ' ', $laundryService->unit)) }}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Provided by</small>
                            <p class="mb-0">{{ $laundryService->provider->name ?? 'Unknown Provider' }}</p>
                        </div>
                        <div>
                            <small class="text-muted">Estimated Time</small>
                            <p class="mb-0">{{ $laundryService->estimated_hours }} hours</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Service Details -->
        <div class="col-md-7">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h1 class="h3 mb-2">{{ $laundryService->name }}</h1>
                            <div class="d-flex align-items-center">
                                <div class="text-warning me-2">
                                    {!! $laundryService->getRatingStarsHtml() !!}
                                </div>
                            </div>
                        </div>
                        <div class="bg-light p-3 rounded-3">
                            <div class="text-muted small mb-1">Price</div>
                            <div class="h3 mb-0 text-primary">₹{{ $laundryService->price }}</div>
                            <div class="text-muted small">{{ ucfirst(str_replace('_', ' ', $laundryService->unit)) }}</div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h2 class="h5 mb-3">Description</h2>
                        <p class="text-muted mb-0">{{ $laundryService->description }}</p>
                    </div>
                    
                    <div class="mb-4">
                        <h2 class="h5 mb-3">Service Details</h2>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="p-2 bg-primary bg-opacity-10 rounded-3">
                                            <i class="fas fa-tshirt fa-fw text-primary"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-muted small">Service Type</div>
                                        <div>{{ ucfirst(str_replace('_', ' ', $laundryService->service_type)) }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="p-2 bg-primary bg-opacity-10 rounded-3">
                                            <i class="fas fa-tag fa-fw text-primary"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-muted small">Pricing</div>
                                        <div>₹{{ $laundryService->price }} / {{ ucfirst(str_replace('_', ' ', $laundryService->unit)) }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="p-2 bg-primary bg-opacity-10 rounded-3">
                                            <i class="fas fa-clock fa-fw text-primary"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-muted small">Turnaround Time</div>
                                        <div>{{ $laundryService->estimated_hours }} hours</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="p-2 bg-primary bg-opacity-10 rounded-3">
                                            <i class="fas fa-calendar-alt fa-fw text-primary"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-muted small">Added On</div>
                                        <div>{{ $laundryService->created_at->format('M d, Y') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h2 class="h5 mb-3">What's Included</h2>
                        <div class="row">
                            @if($laundryService->service_type == 'wash')
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>Regular washing</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>Stain removal</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>Basic ironing</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>Folding</span>
                                    </div>
                                </div>
                            @elseif($laundryService->service_type == 'dry_clean')
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>Dry cleaning</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>Stain treatment</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>Premium pressing</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>Hanging/Folding</span>
                                    </div>
                                </div>
                            @elseif($laundryService->service_type == 'iron')
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>Steam ironing</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>Fabric-appropriate temperature</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>Crisp folding</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>Hanging for delicate items</span>
                                    </div>
                                </div>
                            @elseif($laundryService->service_type == 'fold')
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>Professional folding</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>Special care for delicates</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>Sorting by garment type</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>Eco-friendly packaging</span>
                                    </div>
                                </div>
                            @elseif($laundryService->service_type == 'package_deal')
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>Wash & dry cleaning</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>Premium ironing</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>Express service</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>Special discounted price</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div>
                        @auth
                            @if($laundryService->is_available)
                                <a href="{{ route('orders.create', ['laundry_service' => $laundryService->id]) }}" class="btn btn-primary btn-lg">
                                    <i class="fas fa-shopping-cart me-2"></i> Add to Order
                                </a>
                            @else
                                <button class="btn btn-secondary btn-lg" disabled>
                                    <i class="fas fa-ban me-2"></i> Currently Unavailable
                                </button>
                            @endif
                            
                            @can('edit laundry services')
                                @if($laundryService->provider_id == Auth::id() || Auth::user()->hasRole('admin'))
                                <a href="{{ route('laundry.edit', $laundryService) }}" class="btn btn-outline-secondary btn-lg ms-2">
                                    <i class="fas fa-edit me-2"></i> Edit Service
                                </a>
                                @endif
                            @endcan
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i> Login to Order
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
            
            <!-- Additional Information -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <ul class="nav nav-tabs" id="serviceTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="care-tab" data-bs-toggle="tab" data-bs-target="#care" type="button" role="tab" aria-controls="care" aria-selected="true">Care Instructions</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="faq-tab" data-bs-toggle="tab" data-bs-target="#faq" type="button" role="tab" aria-controls="faq" aria-selected="false">FAQs</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="false">Reviews <span class="badge bg-secondary">{{ $laundryService->reviews->count() }}</span></button>
                        </li>
                    </ul>
                    <div class="tab-content pt-4" id="serviceTabContent">
                        <div class="tab-pane fade show active" id="care" role="tabpanel" aria-labelledby="care-tab">
                            <div class="mb-4">
                                <h3 class="h5 mb-3">Our Process</h3>
                                <p class="text-muted">We take utmost care of your garments during the laundry process:</p>
                                <ul class="text-muted">
                                    <li>Careful sorting by color and fabric type</li>
                                    <li>Pre-treatment of stains and spots</li>
                                    <li>Washing with eco-friendly detergents</li>
                                    <li>Appropriate drying methods for different fabrics</li>
                                    <li>Expert ironing and pressing</li>
                                </ul>
                            </div>
                            
                            <div>
                                <h3 class="h5 mb-3">Care Recommendations</h3>
                                <p class="text-muted mb-3">For the best results and longevity of your garments:</p>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="p-2 bg-light rounded-3">
                                                    <i class="fas fa-tint-slash fa-fw text-primary"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-medium">Separate Stained Items</div>
                                                <div class="small text-muted">Place heavily soiled or stained garments in a separate bag</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="p-2 bg-light rounded-3">
                                                    <i class="fas fa-clipboard-list fa-fw text-primary"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-medium">Note Special Instructions</div>
                                                <div class="small text-muted">Inform us about special care instructions for delicate items</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="p-2 bg-light rounded-3">
                                                    <i class="fas fa-money-bill-wave fa-fw text-primary"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-medium">Empty Pockets</div>
                                                <div class="small text-muted">Remove all items from pockets before sending for laundry</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="p-2 bg-light rounded-3">
                                                    <i class="fas fa-exclamation-triangle fa-fw text-primary"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-medium">Check Care Labels</div>
                                                <div class="small text-muted">Inform us about any "Dry Clean Only" garments</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="faq" role="tabpanel" aria-labelledby="faq-tab">
                            <div class="accordion" id="faqAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="faqOne">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            How do you charge for the service?
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="faqOne" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            @if($laundryService->unit == 'per_kg')
                                                <p>This service is charged by weight. We weigh your laundry before processing and charge accordingly at ₹{{ $laundryService->price }} per kilogram.</p>
                                            @elseif($laundryService->unit == 'per_piece')
                                                <p>This service is charged per item. Each garment is counted as one unit, and we charge ₹{{ $laundryService->price }} per piece.</p>
                                            @elseif($laundryService->unit == 'per_bundle')
                                                <p>This service is charged per bundle. A bundle typically includes a set number of garments (usually 5-10 items depending on the type), and we charge ₹{{ $laundryService->price }} per bundle.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="faqTwo">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            How long will it take to process my order?
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="faqTwo" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            <p>This service has an estimated turnaround time of {{ $laundryService->estimated_hours }} hours. This is the standard processing time from pickup to delivery.</p>
                                            <p>For urgent requirements, you can select the express service option during checkout for an additional fee, which reduces the turnaround time by up to 50%.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="faqThree">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                            How do I package my laundry for pickup?
                                        </button>
                                    </h2>
                                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="faqThree" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            <p>We recommend organizing your laundry as follows:</p>
                                            <ul>
                                                <li>Separate whites, darks, and colors</li>
                                                <li>Place delicate items in a separate bag</li>
                                                <li>Tag items with specific stains for special attention</li>
                                                <li>Ensure all pockets are emptied</li>
                                            </ul>
                                            <p>Our delivery personnel will provide bags for different categories during pickup if required.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="faqFour">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                            What detergents do you use?
                                        </button>
                                    </h2>
                                    <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="faqFour" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            <p>We use high-quality, eco-friendly detergents that are gentle on fabrics but effective on stains. Our detergents are hypoallergenic and free from harsh chemicals.</p>
                                            <p>If you have specific allergies or prefer a particular detergent, you can note this in the special instructions during checkout, and we'll do our best to accommodate your request.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                            @if($laundryService->reviews->count() > 0)
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h3 class="h5 mb-0">Customer Reviews</h3>
                                        <div>
                                            <small class="text-muted">{{ $laundryService->reviews->count() }} reviews</small>
                                        </div>
                                    </div>
                                    
                                    <div class="row align-items-center mb-4">
                                        <div class="col-lg-3 text-center">
                                            <div class="display-4 fw-bold text-warning mb-2">{{ number_format($laundryService->average_rating, 1) }}</div>
                                            <div>
                                                {!! $laundryService->getRatingStarsHtml() !!}
                                            </div>
                                            <div class="text-muted small mt-1">based on {{ $laundryService->reviews->count() }} reviews</div>
                                        </div>
                                        <div class="col-lg-9">
                                            <div class="row align-items-center mb-2">
                                                <div class="col-3 col-md-2 small">5 stars</div>
                                                <div class="col-7 col-md-8">
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $laundryService->reviews->where('rating', 5)->count() / ($laundryService->reviews->count() ?: 1) * 100 }}%" aria-valuenow="{{ $laundryService->reviews->where('rating', 5)->count() / ($laundryService->reviews->count() ?: 1) * 100 }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                                <div class="col-2 small text-end">{{ $laundryService->reviews->where('rating', 5)->count() }}</div>
                                            </div>
                                            <div class="row align-items-center mb-2">
                                                <div class="col-3 col-md-2 small">4 stars</div>
                                                <div class="col-7 col-md-8">
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $laundryService->reviews->where('rating', 4)->count() / ($laundryService->reviews->count() ?: 1) * 100 }}%" aria-valuenow="{{ $laundryService->reviews->where('rating', 4)->count() / ($laundryService->reviews->count() ?: 1) * 100 }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                                <div class="col-2 small text-end">{{ $laundryService->reviews->where('rating', 4)->count() }}</div>
                                            </div>
                                            <div class="row align-items-center mb-2">
                                                <div class="col-3 col-md-2 small">3 stars</div>
                                                <div class="col-7 col-md-8">
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $laundryService->reviews->where('rating', 3)->count() / ($laundryService->reviews->count() ?: 1) * 100 }}%" aria-valuenow="{{ $laundryService->reviews->where('rating', 3)->count() / ($laundryService->reviews->count() ?: 1) * 100 }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                                <div class="col-2 small text-end">{{ $laundryService->reviews->where('rating', 3)->count() }}</div>
                                            </div>
                                            <div class="row align-items-center mb-2">
                                                <div class="col-3 col-md-2 small">2 stars</div>
                                                <div class="col-7 col-md-8">
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $laundryService->reviews->where('rating', 2)->count() / ($laundryService->reviews->count() ?: 1) * 100 }}%" aria-valuenow="{{ $laundryService->reviews->where('rating', 2)->count() / ($laundryService->reviews->count() ?: 1) * 100 }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                                <div class="col-2 small text-end">{{ $laundryService->reviews->where('rating', 2)->count() }}</div>
                                            </div>
                                            <div class="row align-items-center">
                                                <div class="col-3 col-md-2 small">1 star</div>
                                                <div class="col-7 col-md-8">
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $laundryService->reviews->where('rating', 1)->count() / ($laundryService->reviews->count() ?: 1) * 100 }}%" aria-valuenow="{{ $laundryService->reviews->where('rating', 1)->count() / ($laundryService->reviews->count() ?: 1) * 100 }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                                <div class="col-2 small text-end">{{ $laundryService->reviews->where('rating', 1)->count() }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <hr class="my-4">
                                
                                <div>
                                    @foreach($laundryService->reviews()->where('is_approved', true)->latest()->get() as $review)
                                        <div class="d-flex mb-4">
                                            <div class="flex-shrink-0">
                                                <span class="avatar-text bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; font-weight: bold;">
                                                    {{ substr($review->user->name, 0, 1) }}
                                                </span>
                                            </div>
                                            <div class="ms-3 flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <div>
                                                        <strong>{{ $review->user->name }}</strong>
                                                        <small class="text-muted ms-2">{{ $review->created_at->format('M d, Y') }}</small>
                                                    </div>
                                                </div>
                                                <div>
                                                    {!! $review->getRatingStarsHtml() !!}
                                                </div>
                                                <p class="mb-0 mt-2">{{ $review->comment }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <div class="mb-3">
                                        <i class="fas fa-star text-muted fa-3x"></i>
                                    </div>
                                    <h4>No Reviews Yet</h4>
                                    <p class="text-muted">Be the first to review this service!</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection