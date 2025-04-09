<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laundry & Tiffin Service') }} - @yield('title')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .navbar-brand {
            font-weight: 700;
        }
        .nav-link {
            font-weight: 500;
        }
        .hero-section {
            background-color: #f8f9fa;
            padding: 4rem 0;
        }
        .service-card {
            transition: transform 0.3s ease;
            margin-bottom: 1.5rem;
        }
        .service-card:hover {
            transform: translateY(-5px);
        }
        .footer {
            background-color: #212529;
            color: white;
            padding: 3rem 0;
        }
        .footer a {
            color: #adb5bd;
            text-decoration: none;
        }
        .footer a:hover {
            color: white;
        }
        /* Anchor target styling for smooth scrolling */
        .anchor-target {
            display: block;
            position: relative;
            top: -80px; /* Offset for fixed navbar */
            visibility: hidden;
        }
        html {
            scroll-behavior: smooth; /* Enable smooth scrolling */
        }
    </style>

    @yield('styles')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'L&T Services') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">{{ __('Home') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('laundry.index') }}">{{ __('Laundry Services') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('tiffin.index') }}">{{ __('Tiffin Services') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('about') }}">{{ __('About Us') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('contact') }}">{{ __('Contact') }}</a>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('orders.index') }}">
                                    <i class="fas fa-shopping-cart me-1"></i>{{ __('My Orders') }}
                                </a>
                            </li>
                            
                            <!-- Notifications Dropdown -->
                            <li class="nav-item dropdown">
                                @php
                                    $notifications = Auth::user()->unreadNotifications()->take(5)->get();
                                    $notificationCount = Auth::user()->unreadNotifications()->count();
                                @endphp
                                
                                <a id="notification-dropdown" class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="bi bi-bell position-relative">
                                        @if($notificationCount > 0)
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.5rem;">
                                            {{ $notificationCount > 9 ? '9+' : $notificationCount }}
                                        </span>
                                        @endif
                                    </i>
                                </a>
                                
                                <x-notification-dropdown :notifications="$notifications" />
                            </li>
                            
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-1"></i>{{ __('Dashboard') }}
                                    </a>
                                    
                                    <a class="dropdown-item" href="{{ route('notifications.index') }}">
                                        <i class="bi bi-bell me-1"></i>{{ __('Notifications') }}
                                        @if($notificationCount > 0)
                                            <span class="badge bg-danger ms-1">{{ $notificationCount }}</span>
                                        @endif
                                    </a>
                                    
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-1"></i>{{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="container mt-3">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="container mt-3">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        <main class="py-4">
            @yield('content')
        </main>

        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 mb-4 mb-md-0">
                        <h5 class="text-white mb-3">{{ config('app.name', 'L&T Services') }}</h5>
                        <p class="text-muted">Your one-stop solution for laundry and tiffin services.</p>
                        <div class="social-links mt-3">
                            <a href="#" class="me-2"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="me-2"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="me-2"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                    <div class="col-md-2 mb-4 mb-md-0">
                        <h6 class="text-white mb-3">Services</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2"><a href="{{ route('laundry.index') }}">Laundry</a></li>
                            <li class="mb-2"><a href="{{ route('tiffin.index') }}">Tiffin</a></li>
                        </ul>
                    </div>
                    <div class="col-md-2 mb-4 mb-md-0">
                        <h6 class="text-white mb-3">Company</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2"><a href="{{ route('about') }}">About Us</a></li>
                            <li class="mb-2"><a href="{{ route('contact') }}">Contact</a></li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-white mb-3">Contact Us</h6>
                        <p class="text-muted mb-1"><i class="fas fa-map-marker-alt me-2"></i>123 Main Street, City, Country</p>
                        <p class="text-muted mb-1"><i class="fas fa-phone me-2"></i>+1 234 567 8901</p>
                        <p class="text-muted"><i class="fas fa-envelope me-2"></i>info@ltservices.com</p>
                    </div>
                </div>
                <hr class="my-4 bg-secondary">
                <div class="row">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <p class="text-muted mb-0">&copy; {{ date('Y') }} {{ config('app.name', 'L&T Services') }}. All rights reserved.</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <a href="#" class="text-muted me-3">Privacy Policy</a>
                        <a href="#" class="text-muted">Terms of Service</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @yield('scripts')
</body>
</html>