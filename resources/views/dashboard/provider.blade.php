@extends('layouts.app')

@section('title', 'Service Provider Dashboard')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">Service Provider Dashboard</h1>
            <p class="text-muted">Welcome back, {{ Auth::user()->name }}. Here's an overview of your services and orders.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <div class="btn-group" role="group">
                @if(Auth::user()->hasAnyRole(['service_provider', 'laundry_provider']))
                <a href="{{ route('laundry.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Add Laundry Service
                </a>
                @endif
                @if(Auth::user()->hasAnyRole(['service_provider', 'tiffin_provider']))
                <a href="{{ route('tiffin.create') }}" class="btn btn-success">
                    <i class="fas fa-plus me-1"></i> Add Tiffin Service
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-4 mb-md-0">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-3">
                                <i class="fas fa-shopping-cart fa-2x text-primary"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="card-subtitle text-muted mb-1">Assigned Orders</h6>
                            <h2 class="card-title mb-0">{{ $orderStats['assigned'] }}</h2>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('orders.index') }}" class="text-decoration-none small">
                            View all orders <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4 mb-md-0">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="bg-warning bg-opacity-10 p-3 rounded-3">
                                <i class="fas fa-spinner fa-2x text-warning"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="card-subtitle text-muted mb-1">Pending</h6>
                            <h2 class="card-title mb-0">{{ $orderStats['pending'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4 mb-md-0">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="bg-info bg-opacity-10 p-3 rounded-3">
                                <i class="fas fa-clock fa-2x text-info"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="card-subtitle text-muted mb-1">Processing</h6>
                            <h2 class="card-title mb-0">{{ $orderStats['processing'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="bg-success bg-opacity-10 p-3 rounded-3">
                                <i class="fas fa-check-circle fa-2x text-success"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="card-subtitle text-muted mb-1">Completed</h6>
                            <h2 class="card-title mb-0">{{ $orderStats['completed'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Orders -->
        <div class="col-md-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Assigned Orders</h5>
                        <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Order #</th>
                                    <th scope="col">Customer</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('orders.show', $order) }}" class="text-decoration-none fw-medium">
                                            {{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td>{{ $order->user->name }}</td>
                                    <td>
                                        @if($order->order_type == 'laundry')
                                            <span class="badge bg-info text-dark">Laundry</span>
                                        @elseif($order->order_type == 'tiffin')
                                            <span class="badge bg-warning text-dark">Tiffin</span>
                                        @else
                                            <span class="badge bg-secondary">Mixed</span>
                                        @endif
                                    </td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                    <td>
                                        @if($order->status == 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif($order->status == 'processing')
                                            <span class="badge bg-info text-dark">Processing</span>
                                        @elseif($order->status == 'completed')
                                            <span class="badge bg-success">Completed</span>
                                        @elseif($order->status == 'delivered')
                                            <span class="badge bg-primary">Delivered</span>
                                        @elseif($order->status == 'cancelled')
                                            <span class="badge bg-danger">Cancelled</span>
                                        @endif
                                    </td>
                                    <td class="d-flex gap-1">
                                        <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(in_array($order->status, ['pending', 'processing']))
                                        <a href="{{ route('orders.edit', $order) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <p class="text-muted mb-0">No assigned orders found.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- My Services -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">My Services</h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="addServiceDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-plus"></i> Add
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="addServiceDropdown">
                                @if(Auth::user()->hasAnyRole(['service_provider', 'laundry_provider']))
                                <li><a class="dropdown-item" href="{{ route('laundry.create') }}"><i class="fas fa-tshirt me-2"></i> Add Laundry Service</a></li>
                                @endif
                                @if(Auth::user()->hasAnyRole(['service_provider', 'tiffin_provider']))
                                <li><a class="dropdown-item" href="{{ route('tiffin.create') }}"><i class="fas fa-utensils me-2"></i> Add Tiffin Service</a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($services->isEmpty())
                        <div class="text-center py-4">
                            <i class="fas fa-store fa-3x text-muted mb-3"></i>
                            <p class="mb-3">You haven't added any services yet.</p>
                            <div class="d-grid gap-2">
                                @if(Auth::user()->hasAnyRole(['service_provider', 'laundry_provider']))
                                <a href="{{ route('laundry.create') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-tshirt me-2"></i> Add Laundry Service
                                </a>
                                @endif
                                @if(Auth::user()->hasAnyRole(['service_provider', 'tiffin_provider']))
                                <a href="{{ route('tiffin.create') }}" class="btn btn-outline-success">
                                    <i class="fas fa-utensils me-2"></i> Add Tiffin Service
                                </a>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($services as $service)
                                <div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="d-flex align-items-center mb-1">
                                            @if($service instanceof \App\Models\LaundryService)
                                                <i class="fas fa-tshirt text-info me-2"></i>
                                                <a href="{{ route('laundry.show', $service) }}" class="fw-medium text-decoration-none text-reset">
                                                    {{ $service->name }}
                                                </a>
                                            @else
                                                <i class="fas fa-utensils text-warning me-2"></i>
                                                <a href="{{ route('tiffin.show', $service) }}" class="fw-medium text-decoration-none text-reset">
                                                    {{ $service->name }}
                                                </a>
                                            @endif
                                        </div>
                                        <div class="small text-muted">
                                            ₹{{ $service->price }}
                                            @if($service instanceof \App\Models\LaundryService)
                                                / {{ $service->unit }}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="d-flex gap-1">
                                        @if($service instanceof \App\Models\LaundryService)
                                            <a href="{{ route('laundry.edit', $service) }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('tiffin.edit', $service) }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-shopping-cart me-2"></i> View All Orders
                        </a>
                        @if(Auth::user()->hasAnyRole(['service_provider', 'laundry_provider']))
                        <a href="{{ route('laundry.index') }}" class="btn btn-outline-info">
                            <i class="fas fa-tshirt me-2"></i> View Laundry Services
                        </a>
                        @endif
                        @if(Auth::user()->hasAnyRole(['service_provider', 'tiffin_provider']))
                        <a href="{{ route('tiffin.index') }}" class="btn btn-outline-warning">
                            <i class="fas fa-utensils me-2"></i> View Tiffin Services
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection