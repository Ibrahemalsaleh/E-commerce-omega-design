@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('header', 'Overview')

@section('content')
<!-- ===== Top Navbar with Improved Design ===== -->
<nav class="navbar navbar-expand-lg bg-gradient-primary text-white shadow-sm mb-4 rounded">
    <div class="container-fluid">
        <a class="navbar-brand text-white font-weight-bold" href="{{ route('home') }}">
            <i class="fas fa-paint-roller me-2"></i>OMEGA DECORATION AND DESIGN
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link text-white active" aria-current="page" href="{{ route('home') }}">
                        <i class="fas fa-home fa-fw me-1"></i>Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('admin.products.index') }}">
                        <i class="fas fa-box fa-fw me-1"></i>Products
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('admin.orders.index') }}">
                        <i class="fas fa-shopping-cart fa-fw me-1"></i>Orders
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('admin.users.index') }}">
                        <i class="fas fa-users fa-fw me-1"></i>Users
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- ===== Stats Cards with Hover Effects ===== -->
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm h-100 transition-all hover-scale">
            <div class="card-body position-relative">
                <div class="row">
                    <div class="col-8">
                        <h5 class="card-title text-primary fw-bold mb-0">Products</h5>
                        <p class="text-muted small mb-2">Total inventory items</p>
                        <h2 class="display-6 fw-bold mb-0">{{ $productsCount }}</h2>
                    </div>
                    <div class="col-4 text-end">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3 d-inline-block">
                            <i class="fas fa-boxes fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-primary mt-3 stretched-link">
                    <i class="fas fa-arrow-right me-1"></i>View All
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm h-100 transition-all hover-scale">
            <div class="card-body position-relative">
                <div class="row">
                    <div class="col-8">
                        <h5 class="card-title text-success fw-bold mb-0">Orders</h5>
                        <p class="text-muted small mb-2">Total customer orders</p>
                        <h2 class="display-6 fw-bold mb-0">{{ $ordersCount }}</h2>
                    </div>
                    <div class="col-4 text-end">
                        <div class="bg-success bg-opacity-10 rounded-circle p-3 d-inline-block">
                            <i class="fas fa-shopping-bag fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-success mt-3 stretched-link">
                    <i class="fas fa-arrow-right me-1"></i>View All
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm h-100 transition-all hover-scale">
            <div class="card-body position-relative">
                <div class="row">
                    <div class="col-8">
                        <h5 class="card-title text-info fw-bold mb-0">Users</h5>
                        <p class="text-muted small mb-2">Total registered users</p>
                        <h2 class="display-6 fw-bold mb-0">{{ $usersCount }}</h2>
                    </div>
                    <div class="col-4 text-end">
                        <div class="bg-info bg-opacity-10 rounded-circle p-3 d-inline-block">
                            <i class="fas fa-user-friends fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-info mt-3 stretched-link">
                    <i class="fas fa-arrow-right me-1"></i>View All
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm h-100 transition-all hover-scale">
            <div class="card-body position-relative">
                <div class="row">
                    <div class="col-8">
                        <h5 class="card-title text-warning fw-bold mb-0">Total Sales</h5>
                        <p class="text-muted small mb-2">Revenue generated</p>
                        <h2 class="display-6 fw-bold mb-0">{{ number_format($totalSales, 2) }} JOD</h2>
                    </div>
                    <div class="col-4 text-end">
                        <div class="bg-warning bg-opacity-10 rounded-circle p-3 d-inline-block">
                            <i class="fas fa-dollar-sign fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===== Enhanced Charts Section ===== -->
<div class="row mt-4">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 rounded-3 mb-4">
            <div class="card-header bg-white py-3 border-0">
                <div class="d-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line me-2"></i>Monthly Sales Chart
                    </h6>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light" type="button" id="chartOptions" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="chartOptions">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-download me-2"></i>Export Data</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-print me-2"></i>Print Chart</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="salesChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0 rounded-3 mb-4">
            <div class="card-header bg-white py-3 border-0">
                <div class="d-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie me-2"></i>Order Status
                    </h6>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light" type="button" id="pieChartOptions" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="pieChartOptions">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-download me-2"></i>Export Data</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-print me-2"></i>Print Chart</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="orderStatusChart"></canvas>
                </div>
                <div class="mt-4 text-center small d-flex flex-wrap justify-content-center gap-3">
                    <span class="d-flex align-items-center">
                        <i class="fas fa-circle text-primary me-1"></i> Pending
                    </span>
                    <span class="d-flex align-items-center">
                        <i class="fas fa-circle text-success me-1"></i> Delivered
                    </span>
                    <span class="d-flex align-items-center">
                        <i class="fas fa-circle text-info me-1"></i> Processing
                    </span>
                    <span class="d-flex align-items-center">
                        <i class="fas fa-circle text-warning me-1"></i> Shipped
                    </span>
                    <span class="d-flex align-items-center">
                        <i class="fas fa-circle text-danger me-1"></i> Cancelled
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===== Latest Orders & Best Selling Products with Enhanced Tables ===== -->
<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm border-0 rounded-3 mb-4">
            <div class="card-header bg-white py-3 border-0">
                <div class="d-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-shopping-cart me-2"></i>Latest Orders
                    </h6>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">
                        View All Orders
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">#</th>
                                <th>User</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th class="pe-3">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($latestOrders as $order)
                            <tr>
                                <td class="ps-3">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="text-primary fw-bold">
                                        #{{ $order->id }}
                                    </a>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-light rounded-circle me-2 d-flex align-items-center justify-content-center">
                                            <span class="small text-dark">{{ substr($order->user->first_name, 0, 1) }}</span>
                                        </div>
                                        {{ $order->user->first_name }} {{ $order->user->last_name }}
                                    </div>
                                </td>
                                <td>{{ number_format($order->total_amount, 2) }} JOD</td>
                                <td>
                                    @if($order->status == 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif($order->status == 'processing')
                                        <span class="badge bg-info text-white">Processing</span>
                                    @elseif($order->status == 'shipped')
                                        <span class="badge bg-primary text-white">Shipped</span>
                                    @elseif($order->status == 'delivered')
                                        <span class="badge bg-success text-white">Delivered</span>
                                    @elseif($order->status == 'cancelled')
                                        <span class="badge bg-danger text-white">Cancelled</span>
                                    @endif
                                </td>
                                <td class="pe-3">{{ $order->created_at->format('Y-m-d') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm border-0 rounded-3 mb-4">
            <div class="card-header bg-white py-3 border-0">
                <div class="d-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-trophy me-2"></i>Best Selling Products
                    </h6>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-primary">
                        View All Products
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Product</th>
                                <th>Price</th>
                                <th>Quantity Sold</th>
                                <th class="pe-3">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bestSellingProducts as $product)
                            <tr>
                                <td class="ps-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-product bg-light rounded me-2">
                                            <i class="fas fa-box text-primary"></i>
                                        </div>
                                        <a href="{{ route('admin.products.show', $product->id) }}" class="text-dark fw-medium">
                                            {{ $product->name }}
                                        </a>
                                    </div>
                                </td>
                                <td>{{ number_format($product->price, 2) }} JOD</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1" style="height: 6px;">
                                            <div class="progress-bar bg-success" role="progressbar" 
                                                style="width: {{ min(100, ($product->total_quantity / 100) * 100) }}%"></div>
                                        </div>
                                        <span class="ms-2">{{ $product->total_quantity }}</span>
                                    </div>
                                </td>
                                <td class="pe-3 text-success fw-bold">{{ number_format($product->total_sales, 2) }} JOD</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Custom styling for enhanced dashboard */
    .transition-all {
        transition: all 0.3s ease;
    }
    
    .hover-scale:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(to right, #4e73df, #224abe);
    }
    
    .card-header {
        border-bottom: none;
    }
    
    .avatar-sm {
        width: 32px;
        height: 32px;
    }
    
    .avatar-product {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .table {
        margin-bottom: 0;
    }
    
    .table > :not(caption) > * > * {
        padding: 0.75rem 1rem;
    }
    
    .progress {
        background-color: #eaecf4;
    }
    
    .badge {
        padding: 0.5em 0.75em;
        font-weight: 500;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ===== Enhanced Monthly Sales Chart =====
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($months) !!},
                datasets: [{
                    label: 'Monthly Sales',
                    data: {!! json_encode($monthlySales) !!},
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    borderWidth: 2,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: 'rgba(78, 115, 223, 1)',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
                    pointHoverBorderColor: '#ffffff',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: "rgba(0, 0, 0, 0.05)",
                            zeroLineColor: "rgba(0, 0, 0, 0.05)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        },
                        ticks: {
                            callback: function(value) {
                                return value + ' JOD';
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyColor: "#858796",
                        titleColor: '#6e707e',
                        titleFontSize: 14,
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        padding: 15,
                        displayColors: false,
                        caretPadding: 10,
                        callbacks: {
                            label: function(context) {
                                return 'Sales: ' + context.parsed.y + ' JOD';
                            }
                        }
                    }
                }
            }
        });

        // ===== Enhanced Order Status Chart =====
        const statusCtx = document.getElementById('orderStatusChart').getContext('2d');
        const orderStatusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Delivered', 'Processing', 'Shipped', 'Cancelled'],
                datasets: [{
                    data: [
                        {{ $orderStatusStats['pending'] ?? 0 }},
                        {{ $orderStatusStats['delivered'] ?? 0 }},
                        {{ $orderStatusStats['processing'] ?? 0 }},
                        {{ $orderStatusStats['shipped'] ?? 0 }},
                        {{ $orderStatusStats['cancelled'] ?? 0 }}
                    ],
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],
                    hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#dda20a', '#be2617'],
                    hoverBorderColor: "#ffffff",
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyColor: "#858796",
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        padding: 15,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.parsed + ' orders';
                            }
                        }
                    }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            },
        });
    });
</script>
@endpush