<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'ERP Dashboard'))</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        html, body {
            height: 100%;
        }
        .nav-item, .dropdown-item {
            font-size: 13px;
        }
        body, button, input, select, option, td a, .pagination {
            font-size: 12px !important;
        }
        .form-control, select {
            height: 32px !important;
            padding:1px 15px !important
        }
        .submenu {
            transition: all 0.2s ease-in-out;
        }
        .dataTables_wrapper .dataTables_paginate .pagination .page-link {
            font-size: 12px !important;
        }

        .dataTables_wrapper .dataTables_paginate .pagination .page-item {
            font-size: 12px !important;
        }
        .navbar-shadow {
            box-shadow:
        0 2px 4px rgba(0, 0, 0, 0.1),
        0 6px 10px rgba(0, 0, 0, 0.1),
        0 12px 18px rgba(0, 0, 0, 0.1);
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased d-flex flex-column min-vh-100">

    <!-- Top Navigation -->
    @include('layouts.navigation')

    @isset($header)
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endisset

    <!-- Main Content: Sidebar + Content -->
    <div class="container-fluid flex-grow-1">
        <div class="row min-vh-100">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 bg-dark text-white d-flex flex-column pt-3">
                <h4 class="text-white px-3 py-2">ERP System</h4>
                <ul class="nav flex-column">

                    <!-- Dashboard -->
                    @can('is-admin')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('dashboard') ? 'active text-white bg-primary' : 'text-white' }}"
                           href="{{ route('dashboard') }}">
                            📊 Dashboard
                        </a>
                    </li>
                    @endcan

                    <!-- Inventory -->
                    @can('is-admin')
                    @php $inventoryActive = request()->is('products*'); @endphp
                    <li class="nav-item">
                        <a href="javascript:void(0);"
                           class="nav-link d-flex justify-content-between align-items-center text-white toggle-submenu"
                           data-target="inventoryMenu">
                            📦 Inventory
                            <i class="bi bi-chevron-down"></i>
                        </a>
                        <ul class="list-unstyled ps-3 submenu {{ $inventoryActive ? '' : 'd-none' }}" id="inventoryMenu">
                            <li>
                                <a class="nav-link {{ request()->is('products') ? 'active text-white bg-primary fw-bold' : 'text-white' }}"
                                   href="{{ route('products.index') }}">
                                    📝 All Products
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endcan

                    <!-- Sales Orders -->
                    @if(Gate::allows('is-admin') || Gate::allows('is-salesperson'))
                    @php $ordersActive = request()->is('sales-orders*'); @endphp
                    <li class="nav-item">
                        <a href="javascript:void(0);"
                           class="nav-link d-flex justify-content-between align-items-center text-white toggle-submenu"
                           data-target="ordersMenu">
                            🧾 Sales Orders
                            <i class="bi bi-chevron-down"></i>
                        </a>
                        <ul class="list-unstyled ps-3 submenu {{ $ordersActive ? '' : 'd-none' }}" id="ordersMenu">
                            <li>
                                <a class="nav-link {{ request()->is('sales-orders') ? 'active text-white bg-primary fw-bold' : 'text-white' }}"
                                   href="{{ route('sales-orders.index') }}">
                                    📝 All Orders
                                </a>
                            </li>
                            <li>
                                <a class="nav-link {{ request()->is('sales-orders/create') ? 'active text-white bg-primary fw-bold' : 'text-white' }}"
                                   href="{{ route('sales-orders.create') }}">
                                    ✏ Create Order
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif

                </ul>
            </nav>

            <!-- Page Content -->
            <main class="col-md-9 col-lg-10 px-md-4 py-4">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-light text-center text-muted py-3 border-top mt-auto">
        <div class="container">
            <small>© {{ date('Y') }} Mini ERP System • Built with Laravel 12</small>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.toggle-submenu').forEach(function (toggle) {
                toggle.addEventListener('click', function () {
                    const targetId = this.getAttribute('data-target');
                    const submenu = document.getElementById(targetId);
                    if (submenu) {
                        submenu.classList.toggle('d-none');
                    }
                });
            });
        });
    </script>

    @yield('scripts')
</body>
</html>
