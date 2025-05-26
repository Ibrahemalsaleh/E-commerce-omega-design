@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manage Collections</h1>
        <div>
            <div class="btn-group mr-2">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="TREUE">
                    <i class="fas fa-file-export"></i> Export
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('admin.collections.export.pdf') }}">
                        <i class="fas fa-file-pdf text-danger"></i> Export to PDF
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.collections.export.word') }}">
                        <i class="fas fa-file-word text-primary"></i> Export to Word
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.collections.export.excel') }}">
                        <i class="fas fa-file-excel text-success"></i> Export to Excel
                    </a>
                </div>
            </div>
            <a href="{{ route('admin.collections.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Collection
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Collections</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="collectionsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Collection Name</th>
                            <th>Category</th>
                            <th>Products Count</th>
                            <th>Featured</th>
                            <th>New</th>
                            <th>Created Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($collections as $collection)
                        <tr>
                            <td>{{ $collection->id }}</td>
                            <td>
                                @if($collection->image_path)
                                    <img src="{{ $collection->image_url }}" alt="{{ $collection->name }}" class="img-thumbnail" width="100">
                                @else
                                    <span class="text-muted">No image</span>
                                @endif
                            </td>
                            <td>{{ e($collection->name) }}</td>
                            <td>{{ e($collection->category) ?? 'Not categorized' }}</td>
                            <td>{{ $collection->products_count }}</td>
                            <td>
                                @if($collection->is_featured)
                                    <span class="badge badge-success">Yes</span>
                                @else
                                    <span class="badge badge-secondary">No</span>
                                @endif
                            </td>
                            <td>
                                @if($collection->is_new)
                                    <span class="badge badge-info">Yes</span>
                                @else
                                    <span class="badge badge-secondary">No</span>
                                @endif
                            </td>
                            <td>{{ $collection->created_at->format('Y-m-d') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.collections.edit', $collection) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.collections.show', $collection) }}" class="btn btn-sm btn-outline-secondary" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.collections.destroy', $collection) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this collection?')" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#collectionsTable').DataTable({
            "paging": true,
            "pageLength": 10,
            "searching": true,
            "ordering": true,
            "info": true,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/English.json" // Already set to English
            }
        });
    });
</script>
@endsection