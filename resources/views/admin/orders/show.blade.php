@extends('admin.layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Order Details: #{{ $order->id }}</h1> {{-- Translated title --}}
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li> {{-- Translated breadcrumb --}}
        <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Manage Orders</a></li> {{-- Translated breadcrumb --}}
        <li class="breadcrumb-item active">Order Details: #{{ $order->id }}</li> {{-- Translated breadcrumb --}}
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-info-circle me-1"></i>
            Order Information {{-- Translated card header --}}
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>Customer Information</h5> {{-- Translated section title --}}
                    <p><strong>Customer Name:</strong> {{ $order->user->first_name }} {{ $order->user->last_name }}</p> {{-- Translated label --}}
                    <p><strong>Email:</strong> {{ $order->user->email }}</p> {{-- Translated label --}}
                    <p><strong>Phone Number:</strong> {{ $order->user->phone_number ?? 'Not available' }}</p> {{-- Translated label and text --}}
                </div>
                <div class="col-md-6">
                    <h5>Shipping Information</h5> {{-- Translated section title --}}
                    <p><strong>Shipping Address:</strong> {{ $order->shipping_address }}</p> {{-- Translated label --}}
                    <p><strong>Payment Method:</strong> {{ $order->payment_method }}</p> {{-- Translated label --}}
                    <p><strong>Order Date:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p> {{-- Translated label --}}
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <h5>Order Status</h5> {{-- Translated section title --}}
                    <form action="{{ route('admin.orders.status.update', $order->id) }}" method="POST" class="row g-3 align-items-center"> {{-- Added g-3 for spacing --}}
    @csrf
    @method('PATCH')
                        <div class="col-auto"> {{-- Adjusted column classes for alignment --}}
                            <label for="status" class="col-form-label me-2">Update Status:</label> {{-- Added label for accessibility --}}
                        </div>
                        <div class="col-auto">
                            <select name="status" id="status" class="form-select">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option> {{-- Translated status --}}
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option> {{-- Translated status --}}
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option> {{-- Translated status --}}
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option> {{-- Translated status --}}
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option> {{-- Translated status --}}
                            </select>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">Update Status</button> {{-- Translated button text --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-shopping-cart me-1"></i>
            Order Products {{-- Translated card header --}}
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th> {{-- Translated header --}}
                            <th>Image</th> {{-- Translated header --}}
                            <th>Price</th> {{-- Translated header --}}
                            <th>Quantity</th> {{-- Translated header --}}
                            <th>Total</th> {{-- Translated header --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>
                                <a href="{{ route('admin.products.edit', $item->product_id) }}">{{ $item->product->name }}</a>
                            </td>
                            <td>
                                @if($item->product->image_url)
                                <img src="{{ asset('storage/' . $item->product->image_url) }}" alt="{{ $item->product->name }}" width="50">
                                @else
                                <span class="text-muted">No image</span> {{-- Translated text --}}
                                @endif
                            </td>
                            <td>JOD {{ number_format($item->price_at_order, 2) }}</td> {{-- Currency changed --}}
                            <td>{{ $item->quantity }}</td>
                            <td>JOD {{ number_format($item->price_at_order * $item->quantity, 2) }}</td> {{-- Currency changed --}}
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end"><strong>Grand Total:</strong></td> {{-- Translated label --}}
                            <td><strong>JOD {{ number_format($order->total_amount, 2) }}</strong></td> {{-- Currency changed --}}
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-history me-1"></i>
                    Order History {{-- Translated card header --}}
                </div>
                <div class="card-body">
                    <ul class="timeline">
                        <li>Order Created: {{ $order->created_at->format('Y-m-d H:i') }}</li> {{-- Translated text --}}
                        {{-- Translated comment --}}
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-comments me-1"></i>
                    Notes {{-- Translated card header --}}
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.add-note', $order->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <textarea name="note" class="form-control" rows="3" placeholder="Add a note about this order..."></textarea> {{-- Translated placeholder --}}
                        </div>
                        <button type="submit" class="btn btn-primary">Add Note</button> {{-- Translated button text --}}
                    </form>

                    <hr>

                    <div class="notes-list mt-3">
                        {{-- Translated comment --}}
                        <p class="text-muted">No notes yet.</p> {{-- Translated text --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-4">
    <div class="btn-group me-2">
        <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-file-export me-1"></i> Export
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('admin.orders.exportSinglePdf', $order->id) }}" target="_blank">
                <i class="fas fa-file-pdf me-1"></i> Export to PDF
            </a></li>
            <li><a class="dropdown-item" href="#" onclick="alert('Word export for single order not implemented yet')">
                <i class="fas fa-file-word me-1"></i> Export to Word
            </a></li>
        </ul>
    </div>
    <a href="{{ route('admin.orders.print', $order->id) }}" class="btn btn-info me-2" target="_blank" title="Print Invoice">
        <i class="fas fa-print me-1"></i> Print Order
    </a>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary" title="Back to List">
        <i class="fas fa-arrow-left me-1"></i> Back to List
    </a>
</div>
</div>
@endsection