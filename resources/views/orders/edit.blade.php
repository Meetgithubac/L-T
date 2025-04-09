@extends('layouts.app')

@section('title', 'Edit Order')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2 mb-0">Edit Order #{{ $order->order_number }}</h1>
            <p class="text-muted">Update the order details below.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Order
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <form id="orderForm" action="{{ route('orders.update', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <h3 class="h5 mb-3">Order Status</h3>
                            <select class="form-select" name="status" required>
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <h3 class="h5 mb-3">Delivery Details</h3>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="address" class="form-label">Delivery Address</label>
                                    <textarea class="form-control" id="address" name="address" rows="3" required>{{ $order->address }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Contact Phone</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" value="{{ $order->phone }}" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="scheduled_date" class="form-label">Delivery Date</label>
                                    <input type="date" class="form-control" id="scheduled_date" name="scheduled_date" min="{{ date('Y-m-d') }}" value="{{ $order->scheduled_date }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="scheduled_time" class="form-label">Preferred Time</label>
                                    <select class="form-select" id="scheduled_time" name="scheduled_time" required>
                                        <option value="">Select a time slot</option>
                                        <option value="morning" {{ $order->scheduled_time == 'morning' ? 'selected' : '' }}>Morning (8:00 AM - 12:00 PM)</option>
                                        <option value="afternoon" {{ $order->scheduled_time == 'afternoon' ? 'selected' : '' }}>Afternoon (12:00 PM - 4:00 PM)</option>
                                        <option value="evening" {{ $order->scheduled_time == 'evening' ? 'selected' : '' }}>Evening (4:00 PM - 8:00 PM)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h3 class="h5 mb-3">Additional Instructions</h3>
                            <textarea class="form-control" id="instructions" name="instructions" rows="3" placeholder="Enter any special instructions or requirements...">{{ $order->instructions }}</textarea>
                        </div>
                        
                        <div class="mb-4">
                            <h3 class="h5 mb-3">Payment Method</h3>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="payment_method" id="cashOnDelivery" value="cash_on_delivery" {{ $order->payment_method == 'cash_on_delivery' ? 'checked' : '' }}>
                                <label class="form-check-label" for="cashOnDelivery">
                                    <i class="fas fa-money-bill-wave me-1 text-success"></i> Cash on Delivery
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="payment_method" id="onlinePayment" value="online_payment" {{ $order->payment_method == 'online_payment' ? 'checked' : '' }}>
                                <label class="form-check-label" for="onlinePayment">
                                    <i class="fas fa-credit-card me-1 text-primary"></i> Online Payment
                                </label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h3 class="h5 mb-3">Ordered Items</h3>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Item</th>
                                            <th>Type</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->orderItems as $item)
                                        <tr>
                                            <td>
                                                @if($item->laundry_service_id)
                                                    {{ $item->laundryService->name }}
                                                @elseif($item->tiffin_service_id)
                                                    {{ $item->tiffinService->name }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->laundry_service_id)
                                                    <span class="badge bg-info text-dark">Laundry</span>
                                                @elseif($item->tiffin_service_id)
                                                    <span class="badge bg-success">Tiffin</span>
                                                @endif
                                            </td>
                                            <td>₹{{ number_format($item->price, 2) }}</td>
                                            <td>
                                                <input type="number" 
                                                    name="quantities[{{ $item->id }}]" 
                                                    class="form-control form-control-sm quantity-input" 
                                                    value="{{ $item->quantity }}" 
                                                    data-price="{{ $item->price }}"
                                                    data-id="{{ $item->id }}"
                                                    min="1" 
                                                    style="width: 70px;">
                                            </td>
                                            <td class="item-subtotal" data-id="{{ $item->id }}">
                                                ₹{{ number_format($item->price * $item->quantity, 2) }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Update Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm order-summary">
                <div class="card-body">
                    <h3 class="h5 card-title mb-4">Order Summary</h3>
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span id="subtotal">₹{{ number_format($order->total_amount - 40 - ($order->total_amount * 0.05), 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Delivery Fee</span>
                            <span id="deliveryFee">₹40.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax (5%)</span>
                            <span id="tax">₹{{ number_format($order->total_amount * 0.05, 2) }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold mb-0">
                            <span>Total</span>
                            <span id="totalAmount" class="text-primary">₹{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                    
                    <div class="card bg-light mb-3">
                        <div class="card-body p-3">
                            <h5 class="h6 mb-2">Order Information</h5>
                            <ul class="list-unstyled mb-0 small">
                                <li class="mb-2">
                                    <span class="fw-medium">Order Number:</span> {{ $order->order_number }}
                                </li>
                                <li class="mb-2">
                                    <span class="fw-medium">Created On:</span> {{ $order->created_at->format('M d, Y h:i A') }}
                                </li>
                                <li class="mb-2">
                                    <span class="fw-medium">Customer:</span> {{ $order->user->name }}
                                </li>
                                <li>
                                    <span class="fw-medium">Status:</span>
                                    <span class="badge bg-{{ $order->status == 'pending' ? 'warning text-dark' : ($order->status == 'processing' ? 'info' : ($order->status == 'completed' ? 'success' : ($order->status == 'delivered' ? 'primary' : 'danger'))) }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    @if($order->status === 'cancelled')
                        <div class="alert alert-danger mb-0">
                            <h4 class="h6 mb-2"><i class="fas fa-exclamation-triangle me-2"></i>Order Cancelled</h4>
                            <p class="small mb-0">
                                <strong>Reason:</strong> {{ $order->cancellation_reason ?: 'No reason provided' }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const quantityInputs = document.querySelectorAll('.quantity-input');
        const subtotalDisplay = document.getElementById('subtotal');
        const taxDisplay = document.getElementById('tax');
        const totalAmountDisplay = document.getElementById('totalAmount');
        const deliveryFee = 40; // Fixed delivery fee
        
        // Handle quantity input changes
        quantityInputs.forEach(function(input) {
            input.addEventListener('change', function() {
                if (parseInt(this.value) < 1) {
                    this.value = 1;
                }
                calculateTotals();
            });
        });
        
        function calculateTotals() {
            let subtotal = 0;
            
            quantityInputs.forEach(function(input) {
                const itemId = input.dataset.id;
                const price = parseFloat(input.dataset.price);
                const quantity = parseInt(input.value);
                const itemSubtotal = price * quantity;
                
                subtotal += itemSubtotal;
                
                // Update the subtotal display for this item
                const subtotalElement = document.querySelector(`.item-subtotal[data-id="${itemId}"]`);
                subtotalElement.textContent = `₹${itemSubtotal.toFixed(2)}`;
            });
            
            const tax = subtotal * 0.05; // 5% tax
            const total = subtotal + tax + deliveryFee;
            
            subtotalDisplay.textContent = `₹${subtotal.toFixed(2)}`;
            taxDisplay.textContent = `₹${tax.toFixed(2)}`;
            totalAmountDisplay.textContent = `₹${total.toFixed(2)}`;
            
            // Add a hidden input for the total amount
            let totalInput = document.getElementById('totalAmountInput');
            if (!totalInput) {
                totalInput = document.createElement('input');
                totalInput.type = 'hidden';
                totalInput.name = 'total_amount';
                totalInput.id = 'totalAmountInput';
                document.getElementById('orderForm').appendChild(totalInput);
            }
            totalInput.value = total.toFixed(2);
        }
    });
</script>
@endpush