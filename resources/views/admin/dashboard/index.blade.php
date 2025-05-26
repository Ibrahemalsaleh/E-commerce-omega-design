{{-- resources/views/admin/dashboard/index.blade.php --}}

@extends('admin.layouts.master')

@section('title', 'Dashboard') {{-- Translated title --}}

@section('content')
<div class="container-fluid">
    {{-- General statistics overview --}} {{-- Translated comment --}}
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Sales (Current Month)</div> {{-- Translated text --}}
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($monthlySales, 2) }} JOD</div> {{-- Currency changed --}}
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Sales (Year)</div> {{-- Translated text --}}
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($annualSales, 2) }} JOD</div> {{-- Currency changed --}}
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                New Orders</div> {{-- Translated text --}}
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $newOrders }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                New Users</div> {{-- Translated text --}}
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $newUsers }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Sales evolution chart --}} {{-- Translated comment --}}
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Monthly Sales Chart</h6> {{-- Translated card header --}}
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="monthlySalesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Order status distribution chart --}} {{-- Translated comment --}}
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Order Status</h6> {{-- Translated card header --}}
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="orderStatusChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2">
                            <i class="fas fa-circle text-primary"></i> Pending {{-- Translated text --}}
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-success"></i> Delivered {{-- Translated text --}}
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-info"></i> Processing {{-- Translated text --}}
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-warning"></i> Shipped {{-- Translated text --}}
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-danger"></i> Cancelled {{-- Translated text --}}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Latest Orders --}} {{-- Translated comment --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Latest Orders</h6> {{-- Translated card header --}}
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Order #</th> {{-- Translated header --}}
                                    <th>Customer</th> {{-- Translated header --}}
                                    <th>Amount</th> {{-- Translated header --}}
                                    <th>Status</th> {{-- Translated header --}}
                                    <th>Date</th> {{-- Translated header --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($latestOrders as $order)
                                <tr>
                                    <td><a href="{{ route('admin.orders.show', $order->id) }}">#{{ $order->id }}</a></td>
                                    <td>{{ $order->user->first_name }} {{ $order->user->last_name }}</td>
                                    <td>{{ number_format($order->total_amount, 2) }} JOD</td> {{-- Currency changed --}}
                                    <td>
                                        @if($order->status == 'pending')
                                            <span class="badge badge-warning">Pending</span> {{-- Translated badge text --}}
                                        @elseif($order->status == 'processing')
                                            <span class="badge badge-info">Processing</span> {{-- Translated badge text --}}
                                        @elseif($order->status == 'shipped')
                                            <span class="badge badge-primary">Shipped</span> {{-- Translated badge text --}}
                                        @elseif($order->status == 'delivered')
                                            <span class="badge badge-success">Delivered</span> {{-- Translated badge text --}}
                                        @elseif($order->status == 'cancelled')
                                            <span class="badge badge-danger">Cancelled</span> {{-- Translated badge text --}}
                                        @endif
                                    </td>
                                    <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-primary">View All Orders</a> {{-- Translated button --}}
                    </div>
                </div>
            </div>
        </div>

        {{-- Best Selling Products --}} {{-- Translated comment --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Best Selling Products</h6> {{-- Translated card header --}}
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Product</th> {{-- Translated header --}}
                                    <th>Price</th> {{-- Translated header --}}
                                    <th>Quantity Sold</th> {{-- Translated header --}}
                                    <th>Total</th> {{-- Translated header --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bestSellingProducts as $product)
                                <tr>
                                    <td><a href="{{ route('admin.products.show', $product->id) }}">{{ $product->name }}</a></td>
                                    <td>{{ number_format($product->price, 2) }} JOD</td> {{-- Currency changed --}}
                                    <td>{{ $product->total_quantity }}</td>
                                    <td>{{ number_format($product->total_sales, 2) }} JOD</td> {{-- Currency changed --}}
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-primary">View All Products</a> {{-- Translated button --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
<script>
    // Monthly Sales Chart
    var ctx1 = document.getElementById('monthlySalesChart').getContext('2d');
    var monthlySalesChart = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: {!! json_encode($salesChart['labels']) !!},
            datasets: [{
                label: 'Monthly Sales', {{-- Translated label --}}
                data: {!! json_encode($salesChart['data']) !!},
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderColor: 'rgba(78, 115, 223, 1)',
                pointRadius: 3,
                pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointBorderColor: 'rgba(78, 115, 223, 1)',
                pointHoverRadius: 3,
                pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                pointHitRadius: 10,
                pointBorderWidth: 2,
                lineTension: 0.3
            }]
        },
        options: {
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
                xAxes: [{
                    gridLines: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        maxTicksLimit: 7
                    }
                }],
                yAxes: [{
                    ticks: {
                        maxTicksLimit: 5,
                        padding: 10,
                        callback: function(value, index, values) {
                            return number_format(value) + ' JOD'; {{-- Currency changed --}}
                        }
                    },
                    gridLines: {
                        color: "rgb(234, 236, 244)",
                        zeroLineColor: "rgb(234, 236, 244)",
                        drawBorder: false,
                        borderDash: [2],
                        zeroLineBorderDash: [2]
                    }
                }]
            },
            legend: {
                display: false
            },
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                titleMarginBottom: 10,
                titleFontColor: '#6e707e',
                titleFontSize: 14,
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                intersect: false,
                mode: 'index',
                caretPadding: 10,
                callbacks: {
                    label: function(tooltipItem, chart) {
                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                        return datasetLabel + ': ' + number_format(tooltipItem.yLabel) + ' JOD'; {{-- Currency changed --}}
                    }
                }
            }
        }
    });

    // Order Status Chart
    var ctx2 = document.getElementById('orderStatusChart').getContext('2d');
    var orderStatusChart = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Delivered', 'Processing', 'Shipped', 'Cancelled'], {{-- Translated labels --}}
            datasets: [{
                data: [
                    {{ $orderStatusStats['pending'] }},
                    {{ $orderStatusStats['delivered'] }},
                    {{ $orderStatusStats['processing'] }},
                    {{ $orderStatusStats['shipped'] }},
                    {{ $orderStatusStats['cancelled'] }}
                ],
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],
                hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#dda20a', '#be2617'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
            },
            legend: {
                display: false
            },
            cutoutPercentage: 80,
        },
    });

    // Number formatting function
    function number_format(number, decimals, dec_point, thousands_sep) {
        number = (number + '').replace(',', '').replace(' ', '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function(n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }
</script>
@endsection
