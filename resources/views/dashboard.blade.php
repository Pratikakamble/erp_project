@extends('layouts.erp')
@section('title', 'Dashboard')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h5">Admin Dashboard</h1>
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-success">
                <div class="card-body text-center fw-bolder">
                    <i class="bi bi-card-checklist fs-3 me-2"></i>
                    <h5 class="card-title fw-bold mb-1">Total Orders</h5>
                    <p class="card-text">{{ $totalOrders }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-warning">
                <div class="card-body text-center fw-bolder">
                    <i class="bi bi-cart-check fs-3 me-2"></i>
                    <h5 class="card-title fw-bold mb-1">Total Sales</h5>
                    <p class="card-text">₹ {{ number_format($totalSales, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-danger">
                <div class="card-body text-center fw-bolder">
                    <i class="bi bi-exclamation-triangle-fill fs-3 me-2 text-warning"></i>
                    <h5 class="card-title fw-bold mb-1">Low Stocks Alert (Stock less than 10)</h5>
                    <p class="card-text">{{ $lowStock->count() }} Products</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="card mt-4">
            <div class="card-header">
                <strong>Monthly Revenue Chart</strong>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('revenueChart').getContext('2d');

    const revenueChart = new Chart(ctx, {
        type: 'bar', // change to 'line' for line chart
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [{
                label: 'Monthly Revenue (₹)',
                data: {!! json_encode($data) !!},
                backgroundColor: 'rgba(255, 193, 7, 0.6)',
                borderColor: 'rgba(255, 193, 7, 1)',
                borderWidth: 2,
                tension: 0.4,
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 10000
                    }
                }
            }
        }
    });
});
</script>
@endsection
