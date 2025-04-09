@extends('layouts.app')

@section('title', $tiffinService->name)

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('tiffin.index') }}">Tiffin Services</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $tiffinService->name }}</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <div class="row">
        <!-- Service Image -->
        <div class="col-md-5 mb-4 mb-md-0">
            <div class="card border-0 shadow-sm">
                @if($tiffinService->image)
                <img src="{{ $tiffinService->image }}" class="card-img-top img-fluid" alt="{{ $tiffinService->name }}">
                @else
                <div class="bg-light text-center py-5">
                    <i class="fas fa-utensils fa-6x text-secondary"></i>
                </div>
                @endif
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="badge bg-{{ $tiffinService->is_available ? 'success' : 'danger' }}">
                            {{ $tiffinService->is_available ? 'Available' : 'Unavailable' }}
                        </span>
                        <span class="badge bg-info text-dark">{{ ucfirst($tiffinService->meal_type) }}</span>
                        <span class="badge bg-secondary">{{ ucfirst($tiffinService->cuisine) }}</span>
                        @if($tiffinService->is_vegetarian)
                        <span class="badge bg-success">
                            <i class="fas fa-leaf me-1"></i> Vegetarian
                        </span>
                        @else
                        <span class="badge bg-danger">
                            <i class="fas fa-drumstick-bite me-1"></i> Non-Vegetarian
                        </span>
                        @endif
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Provided by</small>
                            <p class="mb-0">{{ $tiffinService->provider->name ?? 'Unknown Provider' }}</p>
                        </div>
                        <div>
                            <small class="text-muted">Cuisine</small>
                            <p class="mb-0">{{ ucfirst($tiffinService->cuisine) }}</p>
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
                            <h1 class="h3 mb-2">{{ $tiffinService->name }}</h1>
                            <div class="d-flex align-items-center">
                                <div class="text-warning me-2">
                                    {!! $tiffinService->getRatingStarsHtml() !!}
                                </div>
                            </div>
                        </div>
                        <div class="bg-light p-3 rounded-3">
                            <div class="text-muted small mb-1">Price</div>
                            <div class="h3 mb-0 text-primary">₹{{ $tiffinService->price }}</div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h2 class="h5 mb-3">Description</h2>
                        <p class="text-muted mb-0">{{ $tiffinService->description }}</p>
                    </div>
                    
                    <div class="mb-4">
                        <h2 class="h5 mb-3">Menu Items</h2>
                        @if(is_array($tiffinService->menu_items) && count($tiffinService->menu_items) > 0)
                            <div class="row">
                                @foreach($tiffinService->menu_items as $item)
                                    <div class="col-md-6 mb-2">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-utensil-spoon text-primary me-2"></i>
                                            <span>{{ $item }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">Menu information not available</p>
                        @endif
                    </div>
                    
                    <div class="mb-4">
                        <h2 class="h5 mb-3">Service Details</h2>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="p-2 bg-primary bg-opacity-10 rounded-3">
                                            <i class="fas fa-utensils fa-fw text-primary"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-muted small">Meal Type</div>
                                        <div>{{ ucfirst($tiffinService->meal_type) }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="p-2 bg-primary bg-opacity-10 rounded-3">
                                            <i class="fas fa-globe-asia fa-fw text-primary"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-muted small">Cuisine</div>
                                        <div>{{ ucfirst($tiffinService->cuisine) }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="p-2 bg-primary bg-opacity-10 rounded-3">
                                            <i class="fas fa-leaf fa-fw text-primary"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-muted small">Diet Type</div>
                                        <div>{{ $tiffinService->is_vegetarian ? 'Vegetarian' : 'Non-Vegetarian' }}</div>
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
                                        <div>{{ $tiffinService->created_at->format('M d, Y') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h2 class="h5 mb-3">What's Included</h2>
                        <div class="row">
                            @if($tiffinService->meal_type == 'breakfast')
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>Freshly prepared breakfast</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>Morning delivery</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>Disposable cutlery</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>Hygienic packaging</span>
                                    </div>
                                </div>
                            @elseif($tiffinService->meal_type == 'lunch')
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>Complete lunch meal</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>Midday delivery</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>Disposable cutlery</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>Hygienic packaging</span>
                                    </div>
                                </div>
                            @elseif($tiffinService->meal_type == 'dinner')
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>Nutritious dinner meal</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>Evening delivery</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>Disposable cutlery</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>Hygienic packaging</span>
                                    </div>
                                </div>
                            @elseif($tiffinService->meal_type == 'combo')
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>Multiple meals delivery</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>Flexible delivery times</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>Disposable cutlery</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span>Special discount</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div>
                        @auth
                            @if($tiffinService->is_available)
                                <a href="{{ route('orders.create', ['tiffin_service' => $tiffinService->id]) }}" class="btn btn-primary btn-lg">
                                    <i class="fas fa-shopping-cart me-2"></i> Add to Order
                                </a>
                            @else
                                <button class="btn btn-secondary btn-lg" disabled>
                                    <i class="fas fa-ban me-2"></i> Currently Unavailable
                                </button>
                            @endif
                            
                            @can('edit tiffin services')
                                @if($tiffinService->provider_id == Auth::id() || Auth::user()->hasRole('admin'))
                                <a href="{{ route('tiffin.edit', $tiffinService) }}" class="btn btn-outline-secondary btn-lg ms-2">
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
                            <button class="nav-link active" id="nutrition-tab" data-bs-toggle="tab" data-bs-target="#nutrition" type="button" role="tab" aria-controls="nutrition" aria-selected="true">Nutrition & Ingredients</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="faq-tab" data-bs-toggle="tab" data-bs-target="#faq" type="button" role="tab" aria-controls="faq" aria-selected="false">FAQs</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="false">Reviews <span class="badge bg-secondary">{{ $tiffinService->reviews->count() }}</span></button>
                        </li>
                    </ul>
                    <div class="tab-content pt-4" id="serviceTabContent">
                        <div class="tab-pane fade show active" id="nutrition" role="tabpanel" aria-labelledby="nutrition-tab">
                            <div class="mb-4">
                                <h3 class="h5 mb-3">Our Ingredients</h3>
                                <p class="text-muted">We take pride in sourcing the freshest ingredients to prepare nutritious and delicious meals:</p>
                                <ul class="text-muted">
                                    <li>Fresh vegetables from local farmers</li>
                                    <li>High-quality grains and legumes</li>
                                    <li>Antibiotic-free {{ $tiffinService->is_vegetarian ? 'dairy products' : 'meats and dairy products' }}</li>
                                    <li>Native spices and herbs</li>
                                    <li>Organic produce whenever possible</li>
                                </ul>
                            </div>
                            
                            <div>
                                <h3 class="h5 mb-3">Dietary Information</h3>
                                <p class="text-muted mb-3">Our meals are designed to provide a balanced diet with proper nutrition.</p>
                                <div class="row">
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="p-3 border rounded text-center">
                                            <div class="text-primary h5 mb-1">~500-600</div>
                                            <div class="small text-muted">Calories</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="p-3 border rounded text-center">
                                            <div class="text-primary h5 mb-1">~20-25g</div>
                                            <div class="small text-muted">Protein</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="p-3 border rounded text-center">
                                            <div class="text-primary h5 mb-1">~15g</div>
                                            <div class="small text-muted">Healthy Fats</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="p-3 border rounded text-center">
                                            <div class="text-primary h5 mb-1">~60-70g</div>
                                            <div class="small text-muted">Carbohydrates</div>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-muted small mt-2">* Nutritional values are approximate and may vary based on specific menu items.</p>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="faq" role="tabpanel" aria-labelledby="faq-tab">
                            <div class="accordion" id="faqAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="faqOne">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            What are the delivery timings?
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="faqOne" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            <p>Our delivery timings depend on the meal type:</p>
                                            <ul>
                                                <li><strong>Breakfast:</strong> 7:00 AM - 9:00 AM</li>
                                                <li><strong>Lunch:</strong> 12:00 PM - 2:00 PM</li>
                                                <li><strong>Dinner:</strong> 7:00 PM - 9:00 PM</li>
                                            </ul>
                                            <p>You can specify your preferred time slot during checkout.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="faqTwo">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            Can I customize my meal?
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="faqTwo" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            Yes, we offer some customization options. You can specify dietary restrictions, spice level preferences, and allergies in the special instructions section when placing your order. However, major changes to the menu items may not be possible.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="faqThree">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                            Do you offer subscription plans?
                                        </button>
                                    </h2>
                                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="faqThree" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            Yes, we offer weekly and monthly subscription plans at discounted rates. You can select the number of days and meals per day as per your requirement. Subscriptions can be managed from your account dashboard once you've signed up.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="faqFour">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                            How is the food packaged?
                                        </button>
                                    </h2>
                                    <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="faqFour" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            We use eco-friendly, microwave-safe containers that are sealed for freshness and hygiene. Each meal component is packaged separately to maintain texture and flavor. The packaging is designed to keep food warm (or cold) during transit.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                            @if($tiffinService->reviews->count() > 0)
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h3 class="h5 mb-0">Customer Reviews</h3>
                                        <div>
                                            <small class="text-muted">{{ $tiffinService->reviews->count() }} reviews</small>
                                        </div>
                                    </div>
                                    
                                    <div class="row align-items-center mb-4">
                                        <div class="col-lg-3 text-center">
                                            <div class="display-4 fw-bold text-warning mb-2">{{ number_format($tiffinService->average_rating, 1) }}</div>
                                            <div>
                                                {!! $tiffinService->getRatingStarsHtml() !!}
                                            </div>
                                            <div class="text-muted small mt-1">based on {{ $tiffinService->reviews->count() }} reviews</div>
                                        </div>
                                        <div class="col-lg-9">
                                            <div class="row align-items-center mb-2">
                                                <div class="col-3 col-md-2 small">5 stars</div>
                                                <div class="col-7 col-md-8">
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $tiffinService->reviews->where('rating', 5)->count() / ($tiffinService->reviews->count() ?: 1) * 100 }}%" aria-valuenow="{{ $tiffinService->reviews->where('rating', 5)->count() / ($tiffinService->reviews->count() ?: 1) * 100 }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                                <div class="col-2 small text-end">{{ $tiffinService->reviews->where('rating', 5)->count() }}</div>
                                            </div>
                                            <div class="row align-items-center mb-2">
                                                <div class="col-3 col-md-2 small">4 stars</div>
                                                <div class="col-7 col-md-8">
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $tiffinService->reviews->where('rating', 4)->count() / ($tiffinService->reviews->count() ?: 1) * 100 }}%" aria-valuenow="{{ $tiffinService->reviews->where('rating', 4)->count() / ($tiffinService->reviews->count() ?: 1) * 100 }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                                <div class="col-2 small text-end">{{ $tiffinService->reviews->where('rating', 4)->count() }}</div>
                                            </div>
                                            <div class="row align-items-center mb-2">
                                                <div class="col-3 col-md-2 small">3 stars</div>
                                                <div class="col-7 col-md-8">
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $tiffinService->reviews->where('rating', 3)->count() / ($tiffinService->reviews->count() ?: 1) * 100 }}%" aria-valuenow="{{ $tiffinService->reviews->where('rating', 3)->count() / ($tiffinService->reviews->count() ?: 1) * 100 }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                                <div class="col-2 small text-end">{{ $tiffinService->reviews->where('rating', 3)->count() }}</div>
                                            </div>
                                            <div class="row align-items-center mb-2">
                                                <div class="col-3 col-md-2 small">2 stars</div>
                                                <div class="col-7 col-md-8">
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $tiffinService->reviews->where('rating', 2)->count() / ($tiffinService->reviews->count() ?: 1) * 100 }}%" aria-valuenow="{{ $tiffinService->reviews->where('rating', 2)->count() / ($tiffinService->reviews->count() ?: 1) * 100 }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                                <div class="col-2 small text-end">{{ $tiffinService->reviews->where('rating', 2)->count() }}</div>
                                            </div>
                                            <div class="row align-items-center">
                                                <div class="col-3 col-md-2 small">1 star</div>
                                                <div class="col-7 col-md-8">
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $tiffinService->reviews->where('rating', 1)->count() / ($tiffinService->reviews->count() ?: 1) * 100 }}%" aria-valuenow="{{ $tiffinService->reviews->where('rating', 1)->count() / ($tiffinService->reviews->count() ?: 1) * 100 }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                                <div class="col-2 small text-end">{{ $tiffinService->reviews->where('rating', 1)->count() }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <hr class="my-4">
                                
                                <div>
                                    @foreach($tiffinService->reviews()->where('is_approved', true)->latest()->get() as $review)
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