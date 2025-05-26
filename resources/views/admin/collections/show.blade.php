@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Collection Details: {{ e($collection->name) }}</h1>
        <div>
            <a href="{{ route('admin.collections.edit', $collection) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit Collection
            </a>
            <a href="{{ route('admin.collections.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Collection Information</h6>
                </div>
                <div class="card-body">
                    @if($collection->image_url)
                        <div class="text-center mb-4">
                            <img src="{{ $collection->image_url }}" alt="{{ e($collection->name) }}" class="img-fluid" style="max-height: 200px;">
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 40%">Collection ID</th>
                                <td>{{ $collection->id }}</td>
                            </tr>
                            <tr>
                                <th>Collection Name</th>
                                <td>{{ e($collection->name) }}</td>
                            </tr>
                            <tr>
                                <th>Category</th>
                                <td>{{ e($collection->category) ?? 'Not categorized' }}</td>
                            </tr>
                            <tr>
                                <th>Featured Collection</th>
                                <td>
                                    @if($collection->is_featured)
                                        <span class="badge badge-success">Yes</span>
                                    @else
                                        <span class="badge badge-secondary">No</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>New Collection</th>
                                <td>
                                    @if($collection->is_new)
                                        <span class="badge badge-info">Yes</span>
                                    @else
                                        <span class="badge badge-secondary">No</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Products Count</th>
                                <td>{{ $collection->products->count() }}</td>
                            </tr>
                            <tr>
                                <th>Created Date</th>
                                <td>{{ $collection->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Last Update</th>
                                <td>{{ $collection->updated_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        </table>
                    </div>

                    @if($collection->description)
                        <div class="mt-3">
                            <h6 class="font-weight-bold">Description:</h6>
                            <p>{!! nl2br(e($collection->description)) !!}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Collection Products</h6>
                </div>
                <div class="card-body">
                    @if($collection->products->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" id="productsTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Image</th>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($collection->products as $product)
                                    <tr>
                                        <td>{{ $product->id }}</td>
                                        <td>
                                            @if($product->image_url)
                                                <img src="{{ $product->image_url }}" alt="{{ e($product->name) }}" style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <span class="text-muted">No image</span>
                                            @endif
                                        </td>
                                        <td>{{ e($product->name) }}</td>
                                        <td>JOD {{ number_format($product->price, 2) }}</td> {{-- Currency changed here --}}
                                        <td>{{ $product->stock_quantity }}</td>
                                        <td>
                                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-primary" title="Edit Product">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            No products have been added to this collection yet.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#productsTable').DataTable({
            "paging": false,
            "searching": false,
            "info": false,
            "ordering": true,
             "language": { // Ensure DataTables language is English, though not strictly necessary for this configuration
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/English.json"
            }
        });
    });
</script>
@endsection