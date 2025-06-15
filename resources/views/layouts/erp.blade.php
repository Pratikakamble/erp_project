<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'ERP Dashboard') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <style>
        .nav-item, .dropdown-item{
            font-size:13px;
            margin-bottom:0px;
            padding-bottom:0px;
        }
        body,button,input,select,option, td a, .pagination{
            font-size:12px !important;
        }
        .form-control{
            height:32px !important;
        }
    </style>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <div class="container-fluid">
            <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse" id="sidebarMenu" style="height: 100vh">
                <div class="position-sticky pt-3">
                    <h4 class="text-white px-3 py-2">ERP System</h4>
                    <ul class="nav flex-column">

                    <!-- Dashboard -->
                    @can('is-admin')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('dashboard') ? 'active text-white bg-primary' : 'text-white' }}" href="{{ route('dashboard') }}">
                            📊 Dashboard
                        </a>
                    </li>
                    @endcan

                    <!-- Inventory -->
                    @can('is-admin')
                    <li class="nav-item">
                        @php $inventoryActive = request()->is('products*'); @endphp
                        <a class="nav-link d-flex justify-content-between align-items-center text-white"
                        data-bs-toggle="collapse"
                        href="#inventoryMenu"
                        role="button"
                        aria-expanded="{{ $inventoryActive ? 'true' : 'false' }}">
                            📦 Inventory
                            <span class="ms-auto"><i class="bi bi-chevron-down"></i></span>
                        </a>
                        <ul class="collapse list-unstyled ps-3 {{ $inventoryActive ? 'show' : '' }}" id="inventoryMenu">
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
                    <li class="nav-item">
                        @php $ordersActive = request()->is('sales-orders*'); @endphp
                        <a class="nav-link d-flex justify-content-between align-items-center text-white"
                        data-bs-toggle="collapse"
                        href="#ordersMenu"
                        role="button"
                        aria-expanded="{{ $ordersActive ? 'true' : 'false' }}">
                            🧾 Sales Orders
                            <span class="ms-auto"><i class="bi bi-chevron-down"></i></span>
                        </a>
                        <ul class="collapse list-unstyled ps-3 {{ $ordersActive ? 'show' : '' }}" id="ordersMenu">
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
                </div>
            </nav>
            <!-- Content -->
            <main class="col-md-10 ms-sm-auto px-md-4 py-4">
                @yield('content')
            </main>
    </div>
</div>
<footer class="bg-light text-center text-muted py-3 border-top mt-5">
    <div class="container">
        <small>© {{ date('Y') }} Mini ERP System • Built with Laravel 12</small>
    </div>
</footer>
</div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @yield('scripts')
</body>
</html>
