@extends('layouts.app')

@section('title', 'Edit Tiffin Service')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('tiffin.index') }}">Tiffin Services</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('tiffin.show', $tiffinService) }}">{{ $tiffinService->name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h1 class="h3 mb-0">Edit Tiffin Service</h1>
                </div>
                <div class="card-body">
                    <form action="{{ route('tiffin.update', $tiffinService) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-3">
                            <div class="col-md-12 mb-3">
                                <label for="name" class="form-label">Service Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $tiffinService->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="meal_type" class="form-label">Meal Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('meal_type') is-invalid @enderror" id="meal_type" name="meal_type" required>
                                    <option value="">Select Type</option>
                                    <option value="breakfast" {{ old('meal_type', $tiffinService->meal_type) == 'breakfast' ? 'selected' : '' }}>Breakfast</option>
                                    <option value="lunch" {{ old('meal_type', $tiffinService->meal_type) == 'lunch' ? 'selected' : '' }}>Lunch</option>
                                    <option value="dinner" {{ old('meal_type', $tiffinService->meal_type) == 'dinner' ? 'selected' : '' }}>Dinner</option>
                                    <option value="combo" {{ old('meal_type', $tiffinService->meal_type) == 'combo' ? 'selected' : '' }}>Combo Meal</option>
                                </select>
                                @error('meal_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="cuisine" class="form-label">Cuisine <span class="text-danger">*</span></label>
                                <select class="form-select @error('cuisine') is-invalid @enderror" id="cuisine" name="cuisine" required>
                                    <option value="">Select Cuisine</option>
                                    <option value="indian" {{ old('cuisine', $tiffinService->cuisine) == 'indian' ? 'selected' : '' }}>Indian</option>
                                    <option value="chinese" {{ old('cuisine', $tiffinService->cuisine) == 'chinese' ? 'selected' : '' }}>Chinese</option>
                                    <option value="italian" {{ old('cuisine', $tiffinService->cuisine) == 'italian' ? 'selected' : '' }}>Italian</option>
                                    <option value="thai" {{ old('cuisine', $tiffinService->cuisine) == 'thai' ? 'selected' : '' }}>Thai</option>
                                    <option value="mexican" {{ old('cuisine', $tiffinService->cuisine) == 'mexican' ? 'selected' : '' }}>Mexican</option>
                                    <option value="continental" {{ old('cuisine', $tiffinService->cuisine) == 'continental' ? 'selected' : '' }}>Continental</option>
                                    <option value="mixed" {{ old('cuisine', $tiffinService->cuisine) == 'mixed' ? 'selected' : '' }}>Mixed</option>
                                </select>
                                @error('cuisine')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label for="price" class="form-label">Price (₹) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $tiffinService->price) }}" required>
                                </div>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $tiffinService->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label class="form-label">Menu Items <span class="text-danger">*</span></label>
                                <div id="menu-items-container">
                                    @php
                                        $menuItems = old('menu_items', $tiffinService->menu_items ?? []);
                                        if (!is_array($menuItems)) {
                                            $menuItems = [];
                                        }
                                    @endphp
                                    
                                    @forelse($menuItems as $index => $item)
                                        <div class="input-group mb-2 menu-item-row">
                                            <input type="text" class="form-control @error('menu_items.'.$index) is-invalid @enderror" name="menu_items[]" value="{{ $item }}" placeholder="Enter menu item" required>
                                            <button type="button" class="btn btn-outline-danger remove-menu-item"><i class="fas fa-times"></i></button>
                                            @error('menu_items.'.$index)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @empty
                                        <div class="input-group mb-2 menu-item-row">
                                            <input type="text" class="form-control" name="menu_items[]" placeholder="Enter menu item" required>
                                            <button type="button" class="btn btn-outline-danger remove-menu-item"><i class="fas fa-times"></i></button>
                                        </div>
                                    @endforelse
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm" id="add-menu-item">
                                    <i class="fas fa-plus me-1"></i> Add Menu Item
                                </button>
                                @error('menu_items')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label for="image" class="form-label">Service Image</label>
                                @if($tiffinService->image)
                                    <div class="mb-2">
                                        <img src="{{ $tiffinService->image }}" alt="{{ $tiffinService->name }}" class="img-thumbnail" style="max-height: 150px;">
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                                <div class="form-text">Optional. Upload a new image if you want to change the current one. Max size: 2MB. Supported formats: JPG, PNG.</div>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="is_vegetarian" name="is_vegetarian" value="1" {{ old('is_vegetarian', $tiffinService->is_vegetarian) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_vegetarian">Vegetarian</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_available" name="is_available" value="1" {{ old('is_available', $tiffinService->is_available) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_available">Service is Available</label>
                                </div>
                            </div>
                            
                            <div class="col-md-12 mt-3 d-flex justify-content-between">
                                <div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Update Service
                                    </button>
                                    <a href="{{ route('tiffin.show', $tiffinService) }}" class="btn btn-outline-secondary ms-2">
                                        <i class="fas fa-times me-1"></i> Cancel
                                    </a>
                                </div>
                                
                                <!-- Delete Button with Modal -->
                                @can('delete tiffin services')
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteServiceModal">
                                        <i class="fas fa-trash me-1"></i> Delete Service
                                    </button>
                                @endcan
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
@can('delete tiffin services')
<div class="modal fade" id="deleteServiceModal" tabindex="-1" aria-labelledby="deleteServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteServiceModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this service? This action cannot be undone.</p>
                <p class="text-danger fw-bold">Warning: If there are any orders associated with this service, they may be affected.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('tiffin.destroy', $tiffinService) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Service</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endcan

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle dynamic menu items
        const container = document.getElementById('menu-items-container');
        const addButton = document.getElementById('add-menu-item');
        
        // Add new menu item field
        addButton.addEventListener('click', function() {
            const div = document.createElement('div');
            div.className = 'input-group mb-2 menu-item-row';
            div.innerHTML = `
                <input type="text" class="form-control" name="menu_items[]" placeholder="Enter menu item" required>
                <button type="button" class="btn btn-outline-danger remove-menu-item"><i class="fas fa-times"></i></button>
            `;
            container.appendChild(div);
            
            // Focus the new input
            const newInput = div.querySelector('input');
            newInput.focus();
        });
        
        // Remove menu item field
        container.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-menu-item') || e.target.closest('.remove-menu-item')) {
                const row = e.target.closest('.menu-item-row');
                
                // Only remove if there's more than one menu item
                if (container.querySelectorAll('.menu-item-row').length > 1) {
                    row.remove();
                } else {
                    // Clear the field instead of removing it
                    const input = row.querySelector('input');
                    input.value = '';
                    input.focus();
                }
            }
        });
        
        // Form validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(event) {
            let isValid = true;
            
            // Check required fields
            const requiredFields = form.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            // Check price is positive
            const priceField = document.getElementById('price');
            if (parseFloat(priceField.value) <= 0) {
                priceField.classList.add('is-invalid');
                isValid = false;
            }
            
            // Check if there's at least one menu item
            const menuItems = form.querySelectorAll('input[name="menu_items[]"]');
            let hasValidMenuItem = false;
            menuItems.forEach(item => {
                if (item.value.trim()) {
                    hasValidMenuItem = true;
                }
            });
            
            if (!hasValidMenuItem) {
                menuItems.forEach(item => item.classList.add('is-invalid'));
                isValid = false;
            }
            
            if (!isValid) {
                event.preventDefault();
                window.scrollTo(0, 0);
                alert('Please fill out all required fields correctly.');
            }
        });
    });
</script>
@endsection