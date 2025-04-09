@extends('layouts.app')

@section('title', 'Customer Dashboard')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">Customer Dashboard</h1>
            <p class="text-muted">Welcome back, {{ Auth::user()->name }}. Here's an overview of your orders and recommended services.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('orders.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Place New Order
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-4 mb-md-0">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-3">
                                <i class="fas fa-shopping-cart fa-2x text-primary"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="card-subtitle text-muted mb-1">Total Orders</h6>
                            <h2 class="card-title mb-0">{{ $orderStats['total'] }}</h2>
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
        <div class="col-md-4 mb-4 mb-md-0">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="bg-warning bg-opacity-10 p-3 rounded-3">
                                <i class="fas fa-spinner fa-2x text-warning"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="card-subtitle text-muted mb-1">Active Orders</h6>
                            <h2 class="card-title mb-0">{{ $orderStats['pending'] + $orderStats['processing'] }}</h2>
                        </div>
                    </div>
                    <div class="mt-3 d-flex justify-content-between">
                        <small class="text-muted">
                            <i class="fas fa-circle me-1 text-warning"></i> Pending: {{ $orderStats['pending'] }}
                        </small>
                        <small class="text-muted">
                            <i class="fas fa-circle me-1 text-info"></i> Processing: {{ $orderStats['processing'] }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="bg-success bg-opacity-10 p-3 rounded-3">
                                <i class="fas fa-check-circle fa-2x text-success"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="card-subtitle text-muted mb-1">Completed Orders</h6>
                            <h2 class="card-title mb-0">{{ $orderStats['completed'] + $orderStats['delivered'] }}</h2>
                        </div>
                    </div>
                    <div class="mt-3 d-flex justify-content-between">
                        <small class="text-muted">
                            <i class="fas fa-circle me-1 text-success"></i> Completed: {{ $orderStats['completed'] }}
                        </small>
                        <small class="text-muted">
                            <i class="fas fa-circle me-1 text-primary"></i> Delivered: {{ $orderStats['delivered'] }}
                        </small>
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
                    @if($recentOrders->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                            <p class="mb-3">You haven't placed any orders yet.</p>
                            <a href="{{ route('orders.create') }}" class="btn btn-primary">
                                Place Your First Order
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">Order #</th>
                                        <th scope="col">Type</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                    <tr>
                                        <td>
                                            <a href="{{ route('orders.show', $order) }}" class="text-decoration-none fw-medium">
                                                {{ $order->order_number }}
                                            </a>
                                        </td>
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
                                        <td class="d-flex gap-1">
                                            <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($order->status == 'pending')
                                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelOrderModal{{ $order->id }}">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                
                                                <!-- Cancel Order Modal -->
                                                <div class="modal fade" id="cancelOrderModal{{ $order->id }}" tabindex="-1" aria-labelledby="cancelOrderModalLabel{{ $order->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="cancelOrderModalLabel{{ $order->id }}">Cancel Order #{{ $order->order_number }}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="{{ route('orders.cancel', $order) }}" method="POST">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    <p>Are you sure you want to cancel this order?</p>
                                                                    <div class="mb-3">
                                                                        <label for="cancellation_reason" class="form-label">Reason for Cancellation</label>
                                                                        <textarea class="form-control" id="cancellation_reason" name="cancellation_reason" rows="3" required></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-danger">Cancel Order</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recommendations & Quick Actions -->
        <div class="col-md-4">
            <!-- Recommended Laundry Services -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Recommended Laundry Services</h5>
                </div>
                <div class="card-body">
                    @if($recommendedLaundryServices->isEmpty())
                        <div class="text-center py-3">
                            <p class="text-muted mb-0">No laundry services available at the moment.</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($recommendedLaundryServices as $service)
                                <div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $service->name }}</h6>
                                        <p class="mb-0 small text-muted">₹{{ $service->price }} / {{ $service->unit }}</p>
                                    </div>
                                    <a href="{{ route('laundry.show', $service) }}" class="btn btn-sm btn-outline-primary">View</a>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('laundry.index') }}" class="btn btn-outline-info btn-sm">View All Laundry Services</a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recommended Tiffin Services -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Recommended Tiffin Services</h5>
                </div>
                <div class="card-body">
                    @if($recommendedTiffinServices->isEmpty())
                        <div class="text-center py-3">
                            <p class="text-muted mb-0">No tiffin services available at the moment.</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($recommendedTiffinServices as $service)
                                <div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $service->name }}</h6>
                                        <p class="mb-0 small text-muted">₹{{ $service->price }}</p>
                                    </div>
                                    <a href="{{ route('tiffin.show', $service) }}" class="btn btn-sm btn-outline-primary">View</a>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('tiffin.index') }}" class="btn btn-outline-warning btn-sm">View All Tiffin Services</a>
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
                        <a href="{{ route('orders.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus-circle me-2"></i> Place New Order
                        </a>
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-shopping-cart me-2"></i> View All Orders
                        </a>
                        <a href="{{ route('contact') }}" class="btn btn-outline-info">
                            <i class="fas fa-headset me-2"></i> Contact Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection