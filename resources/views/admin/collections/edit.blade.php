@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Collection: {{ e($collection->name) }}</h1>
        <a href="{{ route('admin.collections.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Collection Information</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.collections.update', $collection) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Collection Name<span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $collection->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="category">Category</label>
                    <input type="text" class="form-control @error('category') is-invalid @enderror" id="category" name="category" value="{{ old('category', $collection->category) }}">
                    @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Optional category for grouping collections</small>
                </div>

                <div class="form-group">
                    <label for="description">Collection Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $collection->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="image">Collection Image</label>
                    @if($collection->image_url)
                        <div class="mb-2">
                            <img src="{{ $collection->image_url }}" alt="{{ e($collection->name) }}" style="max-width: 200px; max-height: 200px;">
                        </div>
                    @endif
                    <div class="custom-file">
                        <input type="file" class="custom-file-input @error('image') is-invalid @enderror" id="image" name="image">
                        <label class="custom-file-label" for="image">Choose new image</label>
                    </div>
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Leave empty to keep current image. Supported formats: JPG, PNG, JPEG. Max size: 2MB</small>
                </div>

                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $collection->is_featured) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_featured">Featured Collection</label>
                    </div>
                    <small class="form-text text-muted">Featured collections will be displayed on the homepage</small>
                </div>

                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="is_new" name="is_new" value="1" {{ old('is_new', $collection->is_new) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_new">New Collection</label>
                    </div>
                    <small class="form-text text-muted">Mark as new collection for highlighting</small>
                </div>

                <div class="form-group">
                    <label for="products">Select Products</label>
                    <select class="form-control select2 @error('products') is-invalid @enderror" id="products" name="products[]" multiple>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ in_array($product->id, old('products', $collection->products->pluck('id')->toArray())) ? 'selected' : '' }}>
                                {{ e($product->name) }} (JOD {{ $product->price }})
                            </option>
                        @endforeach
                    </select>
                    @error('products')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary btn-block">Update Collection</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select products",
            allowClear: true
        });

        // Change label text when image is selected
        $('.custom-file-input').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName);
        });
    });
</script>
@endsection