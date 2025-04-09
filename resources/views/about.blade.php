@extends('layouts.app')

@section('title', 'About Us')

@section('content')
<div class="container py-5">
    <div class="row mb-5">
        <div class="col-md-10 mx-auto text-center">
            <h1 class="display-4 fw-bold mb-4">About Us</h1>
            <p class="lead mb-4">We're committed to making your daily life easier with our premium laundry and tiffin services.</p>
        </div>
    </div>
    
    <div class="row align-items-center mb-5">
        <div class="col-md-6 mb-4 mb-md-0">
            <img src="https://via.placeholder.com/600x400" alt="About L&T Services" class="img-fluid rounded-3 shadow">
        </div>
        <div class="col-md-6">
            <h2 class="h3 mb-4">Our Story</h2>
            <p>L&T Services was founded with a simple mission: to provide high-quality, convenient laundry and tiffin services that make people's lives easier. We understand the challenges of balancing work, family, and personal time in today's fast-paced world.</p>
            <p>What started as a small operation has grown into a trusted service provider with a reputation for reliability, quality, and customer satisfaction. Our journey has been guided by our commitment to excellence and our passion for delivering services that exceed expectations.</p>
        </div>
    </div>
    
    <div class="row align-items-center mb-5 flex-md-row-reverse">
        <div class="col-md-6 mb-4 mb-md-0">
            <img src="https://via.placeholder.com/600x400" alt="Our Mission" class="img-fluid rounded-3 shadow">
        </div>
        <div class="col-md-6">
            <h2 class="h3 mb-4">Our Mission & Values</h2>
            <p>Our mission is to simplify daily life by providing convenient, high-quality laundry and tiffin services that save time and reduce stress.</p>
            <ul class="list-unstyled">
                <li class="mb-3"><i class="fas fa-check-circle text-primary me-2"></i> <strong>Quality:</strong> We are committed to delivering the highest quality in every service we provide.</li>
                <li class="mb-3"><i class="fas fa-check-circle text-primary me-2"></i> <strong>Reliability:</strong> We understand the importance of timely service and strive to be punctual and dependable.</li>
                <li class="mb-3"><i class="fas fa-check-circle text-primary me-2"></i> <strong>Customer-Centric:</strong> Our customers are at the heart of everything we do, and we prioritize their satisfaction.</li>
                <li><i class="fas fa-check-circle text-primary me-2"></i> <strong>Innovation:</strong> We continuously seek ways to improve our services and embrace new technologies.</li>
            </ul>
        </div>
    </div>
    
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h2 class="h3 mb-4">Our Services</h2>
            <p class="mb-5">We offer a range of services designed to meet your daily needs and make your life easier.</p>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card h-100 border-0 shadow-sm service-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <i class="fas fa-tshirt fa-2x text-primary me-3"></i>
                        <h3 class="card-title h4 mb-0">Laundry Services</h3>
                    </div>
                    <p class="card-text">Our professional laundry services ensure your clothes receive the best care. From everyday wear to delicate fabrics, we handle everything with expertise.</p>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Wash & Fold</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Dry Cleaning</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Ironing & Pressing</li>
                        <li><i class="fas fa-check text-success me-2"></i>Stain Removal & Special Treatments</li>
                    </ul>
                </div>
                <div class="card-footer bg-transparent border-top-0 text-center">
                    <a href="{{ route('laundry.index') }}" class="btn btn-outline-primary">Explore Laundry Services</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card h-100 border-0 shadow-sm service-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <i class="fas fa-utensils fa-2x text-primary me-3"></i>
                        <h3 class="card-title h4 mb-0">Tiffin Services</h3>
                    </div>
                    <p class="card-text">Our tiffin service brings healthy, delicious meals to your doorstep. We prepare meals with fresh ingredients that cater to various dietary preferences.</p>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Breakfast, Lunch & Dinner Options</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Vegetarian & Non-vegetarian Meals</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Customizable Meal Plans</li>
                        <li><i class="fas fa-check text-success me-2"></i>Special Dietary Requirements</li>
                    </ul>
                </div>
                <div class="card-footer bg-transparent border-top-0 text-center">
                    <a href="{{ route('tiffin.index') }}" class="btn btn-outline-primary">Explore Tiffin Services</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h2 class="h3 mb-4">Our Team</h2>
            <p class="mb-5">Meet the dedicated professionals who make L&T Services exceptional.</p>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm service-card text-center">
                <div class="card-body p-4">
                    <img src="https://via.placeholder.com/150" alt="Team Member" class="rounded-circle mb-3" width="150">
                    <h3 class="card-title h5">John Doe</h3>
                    <p class="card-subtitle text-muted small mb-3">Founder & CEO</p>
                    <p class="card-text small">John has over 15 years of experience in the service industry and is passionate about delivering quality services.</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm service-card text-center">
                <div class="card-body p-4">
                    <img src="https://via.placeholder.com/150" alt="Team Member" class="rounded-circle mb-3" width="150">
                    <h3 class="card-title h5">Jane Smith</h3>
                    <p class="card-subtitle text-muted small mb-3">Head of Laundry Operations</p>
                    <p class="card-text small">With expertise in fabric care and laundry processes, Jane ensures our laundry services meet the highest standards.</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm service-card text-center">
                <div class="card-body p-4">
                    <img src="https://via.placeholder.com/150" alt="Team Member" class="rounded-circle mb-3" width="150">
                    <h3 class="card-title h5">Michael Johnson</h3>
                    <p class="card-subtitle text-muted small mb-3">Head Chef</p>
                    <p class="card-text small">Michael is a skilled chef with a passion for creating delicious and nutritious meals for our tiffin service.</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12 text-center">
            <h2 class="h3 mb-4">Why Choose Us?</h2>
            <div class="row mt-4">
                <div class="col-md-4 mb-4">
                    <div class="p-4 h-100 bg-light rounded-3 text-center">
                        <i class="fas fa-medal fa-3x text-primary mb-3"></i>
                        <h4 class="h5">Premium Quality</h4>
                        <p class="small mb-0">We use high-quality products and follow strict processes to ensure the best results.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="p-4 h-100 bg-light rounded-3 text-center">
                        <i class="fas fa-clock fa-3x text-primary mb-3"></i>
                        <h4 class="h5">Timely Service</h4>
                        <p class="small mb-0">We value your time and ensure prompt pickup and delivery as per the scheduled time.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="p-4 h-100 bg-light rounded-3 text-center">
                        <i class="fas fa-smile fa-3x text-primary mb-3"></i>
                        <h4 class="h5">Customer Satisfaction</h4>
                        <p class="small mb-0">Your satisfaction is our priority, and we go the extra mile to exceed your expectations.</p>
                    </div>
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
                <p class="fs-5 mb-0">Join the L&T Services family today and simplify your daily life!</p>
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