@extends('layouts.erp')
@section('title', 'Sales Orders')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold">Sales Order</h2>
    </div>

    <table id="orders-table" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th width="10%">#ID</th>
                <th width="30%">Customer</th>
                <th width="25%">Total (₹)</th>
                <th width="25%">Date</th>
                <th width="10%">Actions</th>
            </tr>
        </thead>
    </table>
@endsection

@section('scripts')
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let table;

        $(function () {
            table = $('#orders-table').DataTable({
            ajax: "{{ route('sales-orders.data') }}",
            order: [[0, 'desc']],
            columns: [
                { data: 'id' },
                { data: 'customer_name' },
                {
                    data: 'total',
                    render: function(data) {
                        return `<div align="right">${data}</div>`;
                    }
                },
                {
                    data: 'created_at',
                    render: function(data) {
                        const date = new Date(data);
                        return date.toLocaleDateString('en-GB'); // Format: DD/MM/YYYY
                    }
                },
                {
                    data: null,
                    render: function(data) {
                        return `
                            <div align="center">
                                <a href="/sales-orders/${data.id}" class="btn btn-xs btn-primary" style="font-size:12px;"><i class="bi bi-eye-fill"></i> View</a>
                            </div>
                        `;
                    }
                }
            ]
        });

        });
    </script>
@endsection
