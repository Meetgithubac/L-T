@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2 mb-0">My Orders</h1>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('orders.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-1"></i>Place New Order
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if($orders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>{{ $order->order_number }}</td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $order->order_type == 'laundry' ? 'info' : ($order->order_type == 'tiffin' ? 'success' : 'primary') }}">
                                            {{ ucfirst($order->order_type) }}
                                        </span>
                                    </td>
                                    <td>₹{{ number_format($order->total_amount, 2) }}</td>
                                    <td>
                                        @switch($order->status)
                                            @case('pending')
                                                <span class="badge bg-warning text-dark">Pending</span>
                                                @break
                                            @case('processing')
                                                <span class="badge bg-info">Processing</span>
                                                @break
                                            @case('completed')
                                                <span class="badge bg-success">Completed</span>
                                                @break
                                            @case('delivered')
                                                <span class="badge bg-primary">Delivered</span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge bg-danger">Cancelled</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        
                                        @if(in_array($order->status, ['pending', 'processing']) && auth()->id() === $order->user_id)
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelOrderModal{{ $order->id }}">
                                                <i class="fas fa-times-circle"></i> Cancel
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
                                                                <p>Are you sure you want to cancel this order? This action cannot be undone.</p>
                                                                <div class="mb-3">
                                                                    <label for="cancellation_reason{{ $order->id }}" class="form-label">Reason for Cancellation</label>
                                                                    <textarea class="form-control" id="cancellation_reason{{ $order->id }}" name="cancellation_reason" rows="3" required></textarea>
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
                                        
                                        @if(auth()->user()->can('edit orders') && in_array($order->status, ['pending', 'processing']))
                                            <a href="{{ route('orders.edit', $order) }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="p-3">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="p-4 text-center">
                    <div class="mb-3">
                        <i class="fas fa-shopping-bag fa-3x text-muted"></i>
                    </div>
                    <h3 class="h5 mb-3">No Orders Yet</h3>
                    <p class="text-muted mb-4">You haven't placed any orders yet. Start by browsing our services.</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('laundry.index') }}" class="btn btn-outline-primary">Explore Laundry Services</a>
                        <a href="{{ route('tiffin.index') }}" class="btn btn-outline-success">Explore Tiffin Services</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection