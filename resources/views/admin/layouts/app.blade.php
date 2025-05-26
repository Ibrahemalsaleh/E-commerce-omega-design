<!DOCTYPE html>
<html lang="en" dir="ltr"> {{-- Changed lang and dir to English LTR --}}
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin Dashboard</title> {{-- Translated title suffix --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}"> {{-- Note: The CSS file itself might need LTR adjustments --}}
    <STyle>
        /* Admin Dashboard CSS - Omega Decoration and Design */

/* ===== General Styles ===== */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f8f9fa;
    color: #333;
}

/* ===== Sidebar Styles ===== */
.sidebar {
    background: linear-gradient(180deg, #2c3e50 0%, #1a252f 100%);
    padding: 0;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    position: fixed;
    height: 100vh;
    z-index: 100;
}

.sidebar .nav-link {
    padding: 0.8rem 1rem;
    color: rgba(255, 255, 255, 0.85);
    border-radius: 5px;
    margin: 0.2rem 0.5rem;
    transition: all 0.3s ease;
}

.sidebar .nav-link:hover {
    background-color: rgba(255, 255, 255, 0.1);
    color: #fff;
    transform: translateX(3px);
}

.sidebar .nav-link.active {
    background-color: #3498db !important;
    color: white;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.sidebar .text-center h5 {
    margin-top: 1rem;
    font-weight: 600;
    color: #3498db;
    padding-bottom: 1rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

/* ===== Main Content Area ===== */
main {
    padding-top: 1.5rem;
    min-height: 100vh;
    background-color: #f8f9fa;
}

.border-bottom {
    border-color: #e9ecef !important;
}

/* ===== Dashboard Cards ===== */
.card {
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    border: none !important;
    margin-bottom: 1.5rem;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
}

.card-body {
    padding: 1.5rem;
}

.card-title {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.75rem;
    font-size: 1.1rem;
}

.card-text {
    font-weight: 700;
    font-size: 2rem;
    margin-bottom: 1rem;
    color: #2c3e50;
}

.card.border-primary {
    border-left: 4px solid #3498db !important;
    background: linear-gradient(135deg, rgba(52, 152, 219, 0.1) 0%, rgba(255, 255, 255, 1) 100%);
}

.card.border-success {
    border-left: 4px solid #2ecc71 !important;
    background: linear-gradient(135deg, rgba(46, 204, 113, 0.1) 0%, rgba(255, 255, 255, 1) 100%);
}

.card.border-info {
    border-left: 4px solid #3498db !important;
    background: linear-gradient(135deg, rgba(52, 152, 219, 0.1) 0%, rgba(255, 255, 255, 1) 100%);
}

.card.border-warning {
    border-left: 4px solid #f39c12 !important;
    background: linear-gradient(135deg, rgba(243, 156, 18, 0.1) 0%, rgba(255, 255, 255, 1) 100%);
}

/* ===== Chart Cards ===== */
.card.shadow {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1) !important;
}

.card-header {
    background-color: white;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    padding: 1rem 1.5rem;
}

.card-header h6 {
    font-weight: 700;
    color: #3498db;
    margin: 0;
}

.chart-area, .chart-pie {
    position: relative;
    height: 100%;
    width: 100%;
}

/* ===== Tables ===== */
.table {
    margin-bottom: 0;
}

.table th {
    font-weight: 600;
    background-color: rgba(0, 0, 0, 0.03);
    color: #495057;
    border-bottom-width: 1px;
}

.table td, .table th {
    padding: 0.75rem 1rem;
    vertical-align: middle;
}

.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0, 0, 0, 0.02);
}

.table a {
    color: #3498db;
    text-decoration: none;
}

.table a:hover {
    color: #217dbb;
    text-decoration: underline;
}

/* ===== Badges ===== */
.badge {
    padding: 0.5em 0.7em;
    font-weight: 500;
    border-radius: 30px;
    font-size: 0.75rem;
}

.bg-warning {
    background-color: rgba(255, 193, 7, 0.9) !important;
    color: #212529;
}

.bg-success {
    background-color: rgba(40, 167, 69, 0.9) !important;
}

.bg-danger {
    background-color: rgba(220, 53, 69, 0.9) !important;
}

.bg-info {
    background-color: rgba(23, 162, 184, 0.9) !important;
}

.bg-primary {
    background-color: rgba(0, 123, 255, 0.9) !important;
}

/* ===== Buttons ===== */
.btn-primary {
    background-color: #3498db;
    border-color: #3498db;
}

.btn-primary:hover {
    background-color: #217dbb;
    border-color: #217dbb;
}

.btn-success {
    background-color: #2ecc71;
    border-color: #2ecc71;
}

.btn-success:hover {
    background-color: #25a25a;
    border-color: #25a25a;
}

.btn-info {
    background-color: #3498db;
    border-color: #3498db;
    color: white;
}

.btn-info:hover {
    background-color: #217dbb;
    border-color: #217dbb;
    color: white;
}

/* ===== Navbar ===== */
.navbar {
    padding: 0.5rem 1rem;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
}

.navbar-brand {
    font-weight: 700;
    color: #3498db !important;
}

/* ===== Alert Styles ===== */
.alert {
    border-radius: 10px;
    border: none;
    padding: 1rem 1.5rem;
    margin-bottom: 1.5rem;
}

.alert-success {
    background-color: rgba(46, 204, 113, 0.15);
    color: #1e8449;
}

.alert-danger {
    background-color: rgba(231, 76, 60, 0.15);
    color: #b03a2e;
}

/* ===== Responsive Adjustments ===== */
@media (max-width: 768px) {
    .sidebar {
        position: static;
        height: auto;
        margin-bottom: 1rem;
    }
    
    main {
        padding-top: 1rem;
    }
    
    .card-text {
        font-size: 1.5rem;
    }
}

/* ===== Custom Styles for RTL Support ===== */
[dir="rtl"] .sidebar .nav-link:hover {
    transform: translateX(-3px);
}

[dir="rtl"] .card.border-primary,
[dir="rtl"] .card.border-success,
[dir="rtl"] .card.border-info,
[dir="rtl"] .card.border-warning {
    border-right: 4px solid;
    border-left: none !important;
}

[dir="rtl"] .card.border-primary {
    border-right-color: #3498db !important;
}

[dir="rtl"] .card.border-success {
    border-right-color: #2ecc71 !important;
}

[dir="rtl"] .card.border-info {
    border-right-color: #3498db !important;
}

[dir="rtl"] .card.border-warning {
    border-right-color: #f39c12 !important;
}

/* ===== Custom Icons ===== */
.fa-tachometer-alt, .fa-box, .fa-layer-group, 
.fa-shopping-cart, .fa-users, .fa-comments, 
.fa-envelope, .fa-cog {
    color: #3498db;
}

/* ===== Footer (if needed) ===== */
footer {
    background-color: #fff;
    padding: 1rem 0;
    text-align: center;
    box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.05);
}
    </style>
    @stack('styles')
</head>
<body>
    {{-- Assuming you might want a top navbar here, but based on the provided structure,
         the main content area is shifted by the sidebar, suggesting a sidebar-focused layout.
         If you need a top navbar across the whole layout, it should go here.
         For now, I'll keep the structure as is and focus on translation. --}}

    <div class="container-fluid">
        <div class="row">
            {{-- Added d-flex and flex-column for proper stacking of sticky sidebar content --}}
            <div class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse d-flex flex-column vh-100">
                <div class="position-sticky pt-3 flex-shrink-0"> {{-- Use flex-shrink to prevent content overflow --}}
                    <div class="text-center mb-4">
                        <h5 class="text-white">Dashboard</h5> {{-- Translated Dashboard title --}}
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->is('admin/dashboard*') ? 'active bg-primary' : '' }}" href="{{ route('admin.dashboard') }}"> {{-- Added text-white and bg-primary for active state --}}
                                <i class="fas fa-tachometer-alt me-2"></i> Dashboard {{-- Adjusted margin and translated --}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->is('admin/products*') ? 'active bg-primary' : '' }}" href="{{ route('admin.products.index') }}"> {{-- Added text-white and bg-primary for active state --}}
                                <i class="fas fa-box me-2"></i> Products {{-- Adjusted margin and translated --}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->is('admin/collections*') ? 'active bg-primary' : '' }}" href="{{ route('admin.collections.index') }}"> {{-- Added text-white and bg-primary for active state --}}
                                <i class="fas fa-layer-group me-2"></i> Collections {{-- Adjusted margin and translated --}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->is('admin/orders*') ? 'active bg-primary' : '' }}" href="{{ route('admin.orders.index') }}"> {{-- Added text-white and bg-primary for active state --}}
                                <i class="fas fa-shopping-cart me-2"></i> Orders {{-- Adjusted margin and translated --}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->is('admin/users*') ? 'active bg-primary' : '' }}" href="{{ route('admin.users.index') }}"> {{-- Added text-white and bg-primary for active state --}}
                                <i class="fas fa-users me-2"></i> Users {{-- Adjusted margin and translated --}}
                            </a>
                        </li>
                        <li class="nav-item">
                             <a class="nav-link text-white {{ request()->is('admin/testimonials*') ? 'active bg-primary' : '' }}" href="{{ route('admin.testimonials.index') }}"> {{-- Added text-white and bg-primary for active state --}}
                                 <i class="fas fa-comments me-2"></i> Testimonials {{-- Adjusted margin and translated --}}
                             </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->is('admin/contacts*') ? 'active bg-primary' : '' }}" href="{{ route('admin.contacts.index') }}"> {{-- Added text-white and bg-primary for active state --}}
                                <i class="fas fa-envelope me-2"></i> Contact Messages {{-- Adjusted margin and translated --}}
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->is('admin/settings*') ? 'active bg-primary' : '' }}" href="{{ route('admin.settings.index') }}"> {{-- Added text-white and bg-primary for active state --}}
                                <i class="fas fa-cog me-2"></i> Settings {{-- Adjusted margin and translated --}}
                            </a>
                        </li>
                    </ul>
                </div>
                {{-- Optional: Add logout link at the bottom --}}
                 {{-- <div class="mt-auto py-3 flex-shrink-0">
                     <ul class="nav flex-column">
                         <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-link text-white btn btn-link">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </button>
                            </form>
                         </li>
                     </ul>
                 </div> --}}
            </div>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">@yield('header')</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        @yield('header_buttons')
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert"> {{-- Added dismissible --}}
                        {{ session('success') }}
                         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> {{-- Added close button --}}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert"> {{-- Added dismissible --}}
                        {{ session('error') }}
                         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> {{-- Added close button --}}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    {{-- Using the same Chart.js version as the previous example --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('scripts')
</body>
</html>