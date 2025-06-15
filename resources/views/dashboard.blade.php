@extends('layouts.erp')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">Admin Dashboard</h1>
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-success">
                <div class="card-body text-center fw-bolder">
                    <h5 class="card-title">Total Orders</h5>
                    <p class="card-text">{{ $totalOrders }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-warning">
                <div class="card-body text-center fw-bolder">
                    <h5 class="card-title">Total Sales</h5>
                    <p class="card-text">₹ {{ number_format($totalSales, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-danger">
                <div class="card-body text-center fw-bolder">
                    <h5 class="card-title">Low Stocks Alert(Stock less than 10)</h5>
                    <p class="card-text">{{ $lowStock->count() }} Products</p>
                </div>
            </div>
        </div>
    </div>

@endsection
