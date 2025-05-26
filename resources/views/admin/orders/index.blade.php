@extends('admin.layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Manage Orders</h1>
    <div class="d-flex">
        <div class="dropdown me-2">
            <button class="btn btn-success dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-file-export me-1"></i> Export
            </button>
            <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                <!-- <li><a class="dropdown-item" href="{{ route('admin.orders.export.pdf') }}{{ request('status') ? '?status='.request('status') : '' }}">
                    <i class="fas fa-file-pdf me-1"></i> Export to PDF
                </a></li>
                <li><a class="dropdown-item" href="{{ route('admin.orders.export.excel') }}{{ request('status') ? '?status='.request('status') : '' }}">
                    <i class="fas fa-file-excel me-1"></i> Export to Excel
                </a></li> -->
                <li><a class="dropdown-item" href="{{ route('admin.orders.export.word') }}{{ request('status') ? '?status='.request('status') : '' }}">
                    <i class="fas fa-file-word me-1"></i> Export to Word
                </a></li>
            </ul>
        </div>
        <form action="{{ route('admin.orders.index') }}" method="GET" class="d-inline-flex">
            <select class="form-control me-2" name="status">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>
    </div>
</div>
    

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Orders</h6> {{-- Translated from جميع الطلبات --}}
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="ordersTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Order #</th> {{-- Translated from رقم الطلب --}}
                            <th>Customer</th> {{-- Translated from العميل --}}
                            <th>Total Amount</th> {{-- Translated from المبلغ الإجمالي --}}
                            <th>Payment Method</th> {{-- Translated from طريقة الدفع --}}
                            <th>Status</th> {{-- Translated from الحالة --}}
                            <th>Order Date</th> {{-- Translated from تاريخ الطلب --}}
                            <th>Actions</th> {{-- Translated from الإجراءات --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->user->first_name }} {{ $order->user->last_name }}</td>
                            <td>JOD {{ $order->total_amount }}</td> {{-- Currency changed from ريال to JOD --}}
                            <td>{{ $order->payment_method }}</td>
                            <td>
                                @if($order->status == 'pending')
                                    <span class="badge badge-warning">Pending</span> {{-- Translated from قيد الانتظار --}}
                                @elseif($order->status == 'processing')
                                    <span class="badge badge-info">Processing</span> {{-- Translated from قيد المعالجة --}}
                                @elseif($order->status == 'shipped')
                                    <span class="badge badge-primary">Shipped</span> {{-- Translated from تم الشحن --}}
                                @elseif($order->status == 'delivered')
                                    <span class="badge badge-success">Delivered</span> {{-- Translated from تم التسليم --}}
                                @elseif($order->status == 'cancelled')
                                    <span class="badge badge-danger">Cancelled</span> {{-- Translated from ملغي --}}
                                @endif
                            </td>
                            <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-secondary" title="View Details"> {{-- Added title --}}
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-sm btn-outline-secondary" title="Edit Order"> {{-- Added title --}}
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#ordersTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/English.json" {{-- Changed from Arabic.json to English.json --}}
            },
            "order": [[0, 'asc']]
        });
    });
</script>
@endsection