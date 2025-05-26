{{-- resources/views/admin/products/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Add New Product') {{-- Translated title --}}

@section('header', 'Add New Product') {{-- Translated header --}}

@section('header_buttons')
    <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-secondary">
        <i class="fas fa-arrow-left me-2"></i> Back to Products List {{-- Translated button text and adjusted icon margin for LTR --}}
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name</label> {{-- Translated label --}}
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Product Description</label> {{-- Translated label --}}
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                        @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price" class="form-label">Price</label> {{-- Translated label --}}
                                <div class="input-group">
                                    <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" required>
                                    <span class="input-group-text">JOD</span> {{-- Changed currency --}}
                                </div>
                                @error('price')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="discount_price" class="form-label">Discount Price (Optional)</label> {{-- Translated label --}}
                                <div class="input-group">
                                    <input type="number" step="0.01" class="form-control @error('discount_price') is-invalid @enderror" id="discount_price" name="discount_price" value="{{ old('discount_price') }}">
                                    <span class="input-group-text">JOD</span> {{-- Changed currency --}}
                                </div>
                                @error('discount_price')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="stock_quantity" class="form-label">Stock Quantity</label> {{-- Translated label --}}
                        <input type="number" min="0" class="form-control @error('stock_quantity') is-invalid @enderror" id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', 0) }}" required>
                        @error('stock_quantity')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Collections</label> {{-- Translated label --}}
                        @foreach($collections as $collection)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="collections[]" value="{{ $collection->id }}" id="collection_{{ $collection->id }}" {{ in_array($collection->id, old('collections', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="collection_{{ $collection->id }}">
                                    {{ $collection->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="image" class="form-label">Product Image</label> {{-- Translated label --}}
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                        @error('image')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="card mt-4">
                        <div class="card-header">Product Properties</div> {{-- Translated card header --}}
                        <div class="card-body">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" {{ old('is_featured') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">
                                    Show in Featured Products {{-- Translated label --}}
                                </label>
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="is_new_arrival" id="is_new_arrival" {{ old('is_new_arrival') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_new_arrival">
                                    Show in New Arrivals {{-- Translated label --}}
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_bestseller" id="is_bestseller" {{ old('is_bestseller') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_bestseller">
                                    Show in Bestsellers {{-- Translated label --}}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i> Create Product {{-- Translated button text and adjusted icon margin for LTR --}}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add any JavaScript for product form here, like a WYSIWYG editor for description
    });
</script>
@endpush