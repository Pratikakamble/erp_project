@extends('layouts.erp')
@section('title', 'Sales Invoice')
@section('content')
<h2 class="d-flex justify-content-between align-items-center">
    Sales Order #{{ $sales_order->id }}
    <a href="{{ route('sales-orders.pdf', $sales_order->id) }}" class="btn btn-sm btn-outline-success">
        🧾 Download PDF
    </a>
</h2>

<div class="mb-3">
    <strong>Customer:</strong> {{ $sales_order->customer_name }}<br>
    <strong>Date:</strong> {{ $sales_order->created_at->format('d M Y, h:i A') }}<br>
    <strong>Total:</strong> ₹ {{ number_format($sales_order->total, 2) }}
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th class="text-center">#</th>
            <th>Product</th>
            <th>Unit Price (₹)</th>
            <th>Quantity</th>
            <th>Subtotal (₹)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sales_order->items as $key=>$item)
        <tr>
            <td align="center">{{ ++$key }}</td>
            <td>{{ $item->product->name }}</td>
            <td align="right">{{ number_format($item->price, 2) }}</td>
            <td  align="center">{{ $item->quantity }}</td>
            <td align="right">{{ number_format($item->subtotal, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
     <tfoot>
            <tr>
                <th colspan="4" style="text-align: right;">Total:</th>
                <th style="text-align: right !important;">₹ {{ number_format($sales_order->total, 2) }}</th>
            </tr>
        </tfoot>
</table>

<a href="{{ route('sales-orders.index') }}" class="btn btn-secondary">← Back to Orders</a>
@endsection


