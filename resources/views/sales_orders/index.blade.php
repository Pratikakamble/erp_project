@extends('layouts.erp')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><b>Sales Order</b></h2>
    </div>

    <table id="orders-table" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#ID</th>
                <th>Customer</th>
                <th>Total (₹)</th>
                <th>Date</th>
                <th>Actions</th>
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
                columns: [
                    { data: 'id' },
                    { data: 'customer_name' },
                    { data: 'total' },
                    {
                        data: 'created_at',
                        render: function(data) {
                            const date = new Date(data);
                            return date.toLocaleDateString('en-GB'); // Outputs: DD/MM/YYYY
                            // For US format: 'en-US' → MM/DD/YYYY
                        }
                    },
                    {
                        data: null,
                        render: function(data) {
                        return `
                        <a href="/sales-orders/${data.id}" class="btn btn-xs btn-primary">View</a>
                    `;
                        },
                    }
                ]
            });
        });
    </script>
@endsection
