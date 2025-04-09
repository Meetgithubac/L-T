@extends('layouts.app')

@section('title', 'Add Laundry Service')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('laundry.index') }}">Laundry Services</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add New Service</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h1 class="h3 mb-0">Add Laundry Service</h1>
                </div>
                <div class="card-body">
                    <form action="{{ route('laundry.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row g-3">
                            <div class="col-md-12 mb-3">
                                <label for="name" class="form-label">Service Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="service_type" class="form-label">Service Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('service_type') is-invalid @enderror" id="service_type" name="service_type" required>
                                    <option value="">Select Type</option>
                                    <option value="wash" {{ old('service_type') == 'wash' ? 'selected' : '' }}>Wash</option>
                                    <option value="dry_clean" {{ old('service_type') == 'dry_clean' ? 'selected' : '' }}>Dry Clean</option>
                                    <option value="iron" {{ old('service_type') == 'iron' ? 'selected' : '' }}>Iron</option>
                                    <option value="fold" {{ old('service_type') == 'fold' ? 'selected' : '' }}>Fold</option>
                                    <option value="package_deal" {{ old('service_type') == 'package_deal' ? 'selected' : '' }}>Package Deal</option>
                                </select>
                                @error('service_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="unit" class="form-label">Pricing Unit <span class="text-danger">*</span></label>
                                <select class="form-select @error('unit') is-invalid @enderror" id="unit" name="unit" required>
                                    <option value="">Select Unit</option>
                                    <option value="per_kg" {{ old('unit') == 'per_kg' ? 'selected' : '' }}>Per Kilogram</option>
                                    <option value="per_piece" {{ old('unit') == 'per_piece' ? 'selected' : '' }}>Per Piece</option>
                                    <option value="per_bundle" {{ old('unit') == 'per_bundle' ? 'selected' : '' }}>Per Bundle</option>
                                </select>
                                @error('unit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Price (₹) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" required>
                                </div>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="estimated_hours" class="form-label">Estimated Hours <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" step="1" min="1" class="form-control @error('estimated_hours') is-invalid @enderror" id="estimated_hours" name="estimated_hours" value="{{ old('estimated_hours', 24) }}" required>
                                    <span class="input-group-text">hours</span>
                                </div>
                                @error('estimated_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label for="image" class="form-label">Service Image</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                                <div class="form-text">Optional. Upload an image that represents your service. Max size: 2MB. Supported formats: JPG, PNG.</div>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_available" name="is_available" value="1" {{ old('is_available', '1') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_available">Service is Available</label>
                                </div>
                            </div>
                            
                            <div class="col-md-12 mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Save Service
                                </button>
                                <a href="{{ route('laundry.index') }}" class="btn btn-outline-secondary ms-2">
                                    <i class="fas fa-times me-1"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Simple client-side validation
    document.addEventListener('DOMContentLoaded', function() {
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
            
            if (!isValid) {
                event.preventDefault();
                window.scrollTo(0, 0);
                alert('Please fill out all required fields correctly.');
            }
        });
    });
</script>
@endsection