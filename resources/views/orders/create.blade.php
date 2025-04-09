@extends('layouts.app')

@section('title', 'Create New Order')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" 
          crossorigin="" />
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" 
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" 
            crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize delivery location map
            const map = L.map('delivery-location-map').setView([20.5937, 78.9629], 5); // Default center of India
            
            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
            
            // Create a marker that will be movable
            let marker = null;
            
            // Function to update marker position and hidden form fields
            function updateMarkerPosition(lat, lng) {
                // If marker already exists, update its position
                if (marker) {
                    marker.setLatLng([lat, lng]);
                } else {
                    // Create new marker
                    marker = L.marker([lat, lng], {
                        draggable: true
                    }).addTo(map);
                    
                    // Update coordinates when marker is dragged
                    marker.on('dragend', function(event) {
                        const position = marker.getLatLng();
                        document.getElementById('delivery_latitude').value = position.lat;
                        document.getElementById('delivery_longitude').value = position.lng;
                    });
                }
                
                // Update hidden form fields
                document.getElementById('delivery_latitude').value = lat;
                document.getElementById('delivery_longitude').value = lng;
            }
            
            // Try to get user's current location
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    // Success callback
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        
                        // Update map center and zoom level
                        map.setView([lat, lng], 15);
                        
                        // Place marker at user's location
                        updateMarkerPosition(lat, lng);
                    },
                    // Error callback
                    function(error) {
                        console.warn('Error obtaining location:', error.message);
                    }
                );
            }
            
            // Allow users to click on the map to set delivery location
            map.on('click', function(event) {
                updateMarkerPosition(event.latlng.lat, event.latlng.lng);
            });
        });
    </script>
@endsection

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2 mb-0">Create New Order</h1>
            <p class="text-muted">Please select the services you'd like to order.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Orders
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <form id="orderForm" action="{{ route('orders.store') }}" method="POST">
                        @csrf
                        
                        <h3 class="h5 mb-4">Select Order Type</h3>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="order_type" id="laundryType" value="laundry" checked>
                                    <label class="form-check-label" for="laundryType">
                                        <i class="fas fa-tshirt me-1 text-primary"></i> Laundry Service
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="order_type" id="tiffinType" value="tiffin">
                                    <label class="form-check-label" for="tiffinType">
                                        <i class="fas fa-utensils me-1 text-success"></i> Tiffin Service
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Laundry Services Selection -->
                        <div id="laundryServices" class="service-selection mb-4">
                            <h3 class="h5 mb-3">Select Laundry Services</h3>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 50px;"></th>
                                            <th>Service</th>
                                            <th>Type</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($laundryServices as $service)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input service-checkbox" type="checkbox" name="laundry_services[]" value="{{ $service->id }}" id="laundry{{ $service->id }}" data-price="{{ $service->price }}" data-id="{{ $service->id }}" data-type="laundry">
                                                </div>
                                            </td>
                                            <td>
                                                <label for="laundry{{ $service->id }}" class="fw-medium">{{ $service->name }}</label>
                                                <div class="text-muted small">{{ Str::limit($service->description, 50) }}</div>
                                            </td>
                                            <td><span class="badge bg-info text-dark">{{ ucfirst($service->service_type) }}</span></td>
                                            <td>₹{{ number_format($service->price, 2) }} / {{ $service->unit }}</td>
                                            <td>
                                                <input type="number" name="laundry_quantity[{{ $service->id }}]" class="form-control form-control-sm quantity-input" data-id="{{ $service->id }}" data-type="laundry" min="1" value="1" style="width: 70px;" disabled>
                                            </td>
                                            <td class="subtotal" data-id="{{ $service->id }}" data-type="laundry">₹0.00</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="fas fa-exclamation-circle fa-2x mb-2"></i>
                                                    <p>No laundry services available.</p>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tiffin Services Selection -->
                        <div id="tiffinServices" class="service-selection mb-4" style="display: none;">
                            <h3 class="h5 mb-3">Select Tiffin Services</h3>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 50px;"></th>
                                            <th>Service</th>
                                            <th>Type</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($tiffinServices as $service)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input service-checkbox" type="checkbox" name="tiffin_services[]" value="{{ $service->id }}" id="tiffin{{ $service->id }}" data-price="{{ $service->price }}" data-id="{{ $service->id }}" data-type="tiffin">
                                                </div>
                                            </td>
                                            <td>
                                                <label for="tiffin{{ $service->id }}" class="fw-medium">{{ $service->name }}</label>
                                                <div class="text-muted small">{{ Str::limit($service->description, 50) }}</div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info text-dark">{{ ucfirst($service->meal_type) }}</span>
                                                @if($service->is_vegetarian)
                                                <span class="badge bg-success"><i class="fas fa-leaf"></i> Veg</span>
                                                @endif
                                            </td>
                                            <td>₹{{ number_format($service->price, 2) }}</td>
                                            <td>
                                                <input type="number" name="tiffin_quantity[{{ $service->id }}]" class="form-control form-control-sm quantity-input" data-id="{{ $service->id }}" data-type="tiffin" min="1" value="1" style="width: 70px;" disabled>
                                            </td>
                                            <td class="subtotal" data-id="{{ $service->id }}" data-type="tiffin">₹0.00</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="fas fa-exclamation-circle fa-2x mb-2"></i>
                                                    <p>No tiffin services available.</p>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h3 class="h5 mb-3">Delivery Details</h3>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="address" class="form-label">Delivery Address</label>
                                    <textarea class="form-control" id="address" name="delivery_address" rows="3" required>{{ auth()->user()->address }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Contact Phone</label>
                                    <input type="tel" class="form-control" id="phone" name="delivery_phone" value="{{ auth()->user()->phone }}" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="scheduled_date" class="form-label">Delivery Date</label>
                                    <input type="date" class="form-control" id="scheduled_date" name="scheduled_date" min="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="scheduled_time" class="form-label">Preferred Time</label>
                                    <select class="form-select" id="scheduled_time" name="scheduled_time" required>
                                        <option value="">Select a time slot</option>
                                        <option value="morning">Morning (8:00 AM - 12:00 PM)</option>
                                        <option value="afternoon">Afternoon (12:00 PM - 4:00 PM)</option>
                                        <option value="evening">Evening (4:00 PM - 8:00 PM)</option>
                                    </select>
                                </div>
                                
                                <div class="col-12 mb-3">
                                    <label class="form-label d-flex align-items-center">
                                        <span>Pin Delivery Location on Map</span>
                                        <small class="text-muted ms-2">(Optional - helps with faster delivery)</small>
                                    </label>
                                    <div id="delivery-location-map" style="height: 300px; border-radius: 8px;"></div>
                                </div>
                                
                                <!-- Hidden fields to store the coordinates -->
                                <input type="hidden" id="delivery_latitude" name="delivery_latitude">
                                <input type="hidden" id="delivery_longitude" name="delivery_longitude">
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h3 class="h5 mb-3">Additional Instructions</h3>
                            <textarea class="form-control" id="instructions" name="instructions" rows="3" placeholder="Enter any special instructions or requirements..."></textarea>
                        </div>
                        
                        <div class="mb-4">
                            <h3 class="h5 mb-3">Payment Method</h3>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="payment_method" id="cashOnDelivery" value="cash_on_delivery" checked>
                                <label class="form-check-label" for="cashOnDelivery">
                                    <i class="fas fa-money-bill-wave me-1 text-success"></i> Cash on Delivery
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="payment_method" id="onlinePayment" value="online_payment">
                                <label class="form-check-label" for="onlinePayment">
                                    <i class="fas fa-credit-card me-1 text-primary"></i> Online Payment
                                </label>
                            </div>
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
                            <span id="subtotal">₹0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Delivery Fee</span>
                            <span id="deliveryFee">₹40.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax (5%)</span>
                            <span id="tax">₹0.00</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold mb-0">
                            <span>Total</span>
                            <span id="totalAmount" class="text-primary">₹40.00</span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="termsCheck" required>
                            <label class="form-check-label" for="termsCheck">
                                I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms & Conditions</a>
                            </label>
                        </div>
                    </div>
                    
                    <button type="button" id="placeOrderBtn" class="btn btn-primary btn-lg w-100 mb-3" disabled>
                        Place Order
                    </button>
                    
                    <div class="text-center text-muted small">
                        <p class="mb-0">By placing this order, you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                <div class="alert alert-info">
                    <h4 class="h6 mb-2"><i class="fas fa-info-circle me-2"></i>Need Help?</h4>
                    <p class="small mb-0">If you have questions about placing an order, please contact our customer support at <a href="tel:+1234567890">123-456-7890</a>.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Terms and Conditions Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5>1. Service Agreement</h5>
                <p>By placing an order, you agree to the terms and conditions outlined in this document. Our services are subject to availability and may vary based on location.</p>
                
                <h5>2. Order Cancellation</h5>
                <p>Orders can be cancelled up to 2 hours before the scheduled service time. Cancellations after this period may incur a cancellation fee.</p>
                
                <h5>3. Delivery Times</h5>
                <p>Delivery times are estimates and may vary due to traffic, weather conditions, or other unforeseen circumstances.</p>
                
                <h5>4. Payment</h5>
                <p>Payment is due at the time of service delivery unless otherwise specified. We accept cash and online payments.</p>
                
                <h5>5. Privacy</h5>
                <p>Your personal information is collected and used in accordance with our Privacy Policy, which is available on our website.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" id="acceptTerms">Accept</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const orderType = document.getElementsByName('order_type');
        const laundryServices = document.getElementById('laundryServices');
        const tiffinServices = document.getElementById('tiffinServices');
        const serviceCheckboxes = document.querySelectorAll('.service-checkbox');
        const quantityInputs = document.querySelectorAll('.quantity-input');
        const subtotalElements = document.querySelectorAll('.subtotal');
        const subtotalDisplay = document.getElementById('subtotal');
        const taxDisplay = document.getElementById('tax');
        const totalAmountDisplay = document.getElementById('totalAmount');
        const deliveryFeeDisplay = document.getElementById('deliveryFee');
        const termsCheck = document.getElementById('termsCheck');
        const placeOrderBtn = document.getElementById('placeOrderBtn');
        const orderForm = document.getElementById('orderForm');
        const acceptTermsBtn = document.getElementById('acceptTerms');
        
        // Set default scheduled date to tomorrow
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        document.getElementById('scheduled_date').value = tomorrow.toISOString().split('T')[0];
        
        // Toggle between laundry and tiffin services
        orderType.forEach(function(radio) {
            radio.addEventListener('change', function() {
                if (this.value === 'laundry') {
                    laundryServices.style.display = 'block';
                    tiffinServices.style.display = 'none';
                    // Uncheck all tiffin services
                    document.querySelectorAll('input[name="tiffin_services[]"]').forEach(function(checkbox) {
                        checkbox.checked = false;
                        updateQuantityInputState(checkbox);
                    });
                } else {
                    laundryServices.style.display = 'none';
                    tiffinServices.style.display = 'block';
                    // Uncheck all laundry services
                    document.querySelectorAll('input[name="laundry_services[]"]').forEach(function(checkbox) {
                        checkbox.checked = false;
                        updateQuantityInputState(checkbox);
                    });
                }
                calculateTotal();
            });
        });
        
        // Handle service checkbox changes
        serviceCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                updateQuantityInputState(this);
                calculateTotal();
            });
        });
        
        // Handle quantity input changes
        quantityInputs.forEach(function(input) {
            input.addEventListener('change', function() {
                if (parseInt(this.value) < 1) {
                    this.value = 1;
                }
                calculateTotal();
            });
        });
        
        function updateQuantityInputState(checkbox) {
            const serviceId = checkbox.dataset.id;
            const serviceType = checkbox.dataset.type;
            const quantityInput = document.querySelector(`input[name="${serviceType}_quantity[${serviceId}]"]`);
            
            if (checkbox.checked) {
                quantityInput.disabled = false;
            } else {
                quantityInput.disabled = true;
                quantityInput.value = 1;
            }
        }
        
        function calculateTotal() {
            let subtotal = 0;
            const deliveryFee = 40; // Fixed delivery fee
            
            serviceCheckboxes.forEach(function(checkbox) {
                if (checkbox.checked) {
                    const serviceId = checkbox.dataset.id;
                    const serviceType = checkbox.dataset.type;
                    const price = parseFloat(checkbox.dataset.price);
                    const quantity = parseInt(document.querySelector(`input[name="${serviceType}_quantity[${serviceId}]"]`).value);
                    const itemSubtotal = price * quantity;
                    
                    subtotal += itemSubtotal;
                    
                    // Update the subtotal display for this item
                    const subtotalElement = document.querySelector(`.subtotal[data-id="${serviceId}"][data-type="${serviceType}"]`);
                    subtotalElement.textContent = `₹${itemSubtotal.toFixed(2)}`;
                } else {
                    const serviceId = checkbox.dataset.id;
                    const serviceType = checkbox.dataset.type;
                    const subtotalElement = document.querySelector(`.subtotal[data-id="${serviceId}"][data-type="${serviceType}"]`);
                    subtotalElement.textContent = `₹0.00`;
                }
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
                orderForm.appendChild(totalInput);
            }
            totalInput.value = total.toFixed(2);
            
            // Enable/disable place order button
            checkPlaceOrderButton();
        }
        
        function checkPlaceOrderButton() {
            let hasSelectedServices = false;
            
            serviceCheckboxes.forEach(function(checkbox) {
                if (checkbox.checked) {
                    hasSelectedServices = true;
                }
            });
            
            placeOrderBtn.disabled = !(hasSelectedServices && termsCheck.checked);
        }
        
        // Handle terms checkbox change
        termsCheck.addEventListener('change', function() {
            checkPlaceOrderButton();
        });
        
        // Handle accept terms button click
        acceptTermsBtn.addEventListener('click', function() {
            termsCheck.checked = true;
            checkPlaceOrderButton();
        });
        
        // Handle place order button click
        placeOrderBtn.addEventListener('click', function() {
            // Prepare the delivery time from date and time slot
            const deliveryDate = document.getElementById('scheduled_date').value;
            const deliveryTimeSlot = document.getElementById('scheduled_time').value;
            
            let deliveryTime = '';
            switch(deliveryTimeSlot) {
                case 'morning':
                    deliveryTime = '10:00';
                    break;
                case 'afternoon':
                    deliveryTime = '14:00';
                    break;
                case 'evening':
                    deliveryTime = '18:00';
                    break;
                default:
                    deliveryTime = '12:00';
            }
            
            // Create hidden field for delivery time
            let deliveryTimeInput = document.getElementById('delivery_time_input');
            if (!deliveryTimeInput) {
                deliveryTimeInput = document.createElement('input');
                deliveryTimeInput.type = 'hidden';
                deliveryTimeInput.name = 'delivery_time';
                deliveryTimeInput.id = 'delivery_time_input';
                orderForm.appendChild(deliveryTimeInput);
            }
            deliveryTimeInput.value = deliveryDate + ' ' + deliveryTime;
            
            // Submit the form
            orderForm.submit();
        });
    });
</script>
@endpush