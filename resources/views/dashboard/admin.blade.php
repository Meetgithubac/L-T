@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">Admin Dashboard</h1>
            <p class="text-muted">Welcome back, {{ Auth::user()->name }}. Here's an overview of the system.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <div class="btn-group" role="group">
                <a href="{{ route('laundry.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Add Laundry Service
                </a>
                <a href="{{ route('tiffin.create') }}" class="btn btn-success">
                    <i class="fas fa-plus me-1"></i> Add Tiffin Service
                </a>
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
                                <i class="fas fa-users fa-2x text-primary"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="card-subtitle text-muted mb-1">Total Users</h6>
                            <h2 class="card-title mb-0">{{ $userStats['total'] }}</h2>
                        </div>
                    </div>
                    <div class="mt-3 d-flex justify-content-between">
                        <small class="text-success">
                            <i class="fas fa-user-tie me-1"></i> {{ $userStats['providers'] }} Providers
                        </small>
                        <small class="text-info">
                            <i class="fas fa-user me-1"></i> {{ $userStats['customers'] }} Customers
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4 mb-md-0">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="bg-success bg-opacity-10 p-3 rounded-3">
                                <i class="fas fa-shopping-cart fa-2x text-success"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="card-subtitle text-muted mb-1">Total Orders</h6>
                            <h2 class="card-title mb-0">{{ $orderStats['total'] }}</h2>
                        </div>
                    </div>
                    <div class="mt-3 d-flex justify-content-between">
                        <small class="text-warning">
                            <i class="fas fa-spinner me-1"></i> {{ $orderStats['pending'] + $orderStats['processing'] }} Active
                        </small>
                        <small class="text-success">
                            <i class="fas fa-check-circle me-1"></i> {{ $orderStats['completed'] + $orderStats['delivered'] }} Completed
                        </small>
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
                                <i class="fas fa-tshirt fa-2x text-info"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="card-subtitle text-muted mb-1">Laundry Services</h6>
                            <h2 class="card-title mb-0">{{ $serviceStats['laundry'] }}</h2>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('laundry.index') }}" class="text-decoration-none small">
                            View all services <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="bg-warning bg-opacity-10 p-3 rounded-3">
                                <i class="fas fa-utensils fa-2x text-warning"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="card-subtitle text-muted mb-1">Tiffin Services</h6>
                            <h2 class="card-title mb-0">{{ $serviceStats['tiffin'] }}</h2>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('tiffin.index') }}" class="text-decoration-none small">
                            View all services <i class="fas fa-arrow-right ms-1"></i>
                        </a>
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
                        <h5 class="mb-0">Recent Orders</h5>
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
                                    <th scope="col">Amount</th>
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
                                    <td>₹{{ number_format($order->total_amount, 2) }}</td>
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
                                    <td>
                                        <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <p class="text-muted mb-0">No recent orders found.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Stats -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Order Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Order Status</h6>
                        <div class="progress mb-2" style="height: 20px">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ ($orderStats['pending'] / max($orderStats['total'], 1)) * 100 }}%" aria-valuenow="{{ $orderStats['pending'] }}" aria-valuemin="0" aria-valuemax="{{ $orderStats['total'] }}">Pending</div>
                            <div class="progress-bar bg-info" role="progressbar" style="width: {{ ($orderStats['processing'] / max($orderStats['total'], 1)) * 100 }}%" aria-valuenow="{{ $orderStats['processing'] }}" aria-valuemin="0" aria-valuemax="{{ $orderStats['total'] }}">Processing</div>
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ ($orderStats['completed'] / max($orderStats['total'], 1)) * 100 }}%" aria-valuenow="{{ $orderStats['completed'] }}" aria-valuemin="0" aria-valuemax="{{ $orderStats['total'] }}">Completed</div>
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ ($orderStats['delivered'] / max($orderStats['total'], 1)) * 100 }}%" aria-valuenow="{{ $orderStats['delivered'] }}" aria-valuemin="0" aria-valuemax="{{ $orderStats['total'] }}">Delivered</div>
                            <div class="progress-bar bg-danger" role="progressbar" style="width: {{ ($orderStats['cancelled'] / max($orderStats['total'], 1)) * 100 }}%" aria-valuenow="{{ $orderStats['cancelled'] }}" aria-valuemin="0" aria-valuemax="{{ $orderStats['total'] }}">Cancelled</div>
                        </div>
                        <div class="d-flex flex-wrap gap-2 justify-content-between mt-3">
                            <small class="d-inline-flex align-items-center">
                                <span class="badge bg-warning me-1">&nbsp;</span> Pending: {{ $orderStats['pending'] }}
                            </small>
                            <small class="d-inline-flex align-items-center">
                                <span class="badge bg-info me-1">&nbsp;</span> Processing: {{ $orderStats['processing'] }}
                            </small>
                            <small class="d-inline-flex align-items-center">
                                <span class="badge bg-success me-1">&nbsp;</span> Completed: {{ $orderStats['completed'] }}
                            </small>
                            <small class="d-inline-flex align-items-center">
                                <span class="badge bg-primary me-1">&nbsp;</span> Delivered: {{ $orderStats['delivered'] }}
                            </small>
                            <small class="d-inline-flex align-items-center">
                                <span class="badge bg-danger me-1">&nbsp;</span> Cancelled: {{ $orderStats['cancelled'] }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('orders.create') }}" class="btn btn-outline-primary">
                            <i class="fas fa-plus-circle me-2"></i> Create New Order
                        </a>
                        <a href="{{ route('laundry.create') }}" class="btn btn-outline-info">
                            <i class="fas fa-tshirt me-2"></i> Add Laundry Service
                        </a>
                        <a href="{{ route('tiffin.create') }}" class="btn btn-outline-warning">
                            <i class="fas fa-utensils me-2"></i> Add Tiffin Service
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection