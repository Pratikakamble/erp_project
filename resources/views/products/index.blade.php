@extends('layouts.erp')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Product Inventory</h2>
        <button class="btn btn-success" onclick="showAddModal()">Add Product</button>
    </div>

    <table id="products-table" class="table table-bordered table-striped">
        <thead>
        <tr><th>ID</th><th>Name</th><th>SKU</th><th>Price</th><th>Qty</th><th>Actions</th></tr>
        </thead>
    </table>

    <!-- Modal -->
    <div class="modal fade" id="productModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="productForm">
                @csrf
                <input type="hidden" id="product_id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label>Name</label>
                        <input class="form-control mb-2" name="name" id="name" placeholder="Name" >
                        <label>SKU</label>
                        <input class="form-control mb-2" name="sku" id="sku" placeholder="SKU" >
                        <label>Price</label>
                        <input class="form-control mb-2" name="price" id="price" type="number" step="0.01" placeholder="Price" >
                        <label>Quantity</label>
                        <input class="form-control mb-2" name="quantity" id="quantity" type="number" placeholder="Quantity" >
                        
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="save_product">Save Product</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
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
            table = $('#products-table').DataTable({
                ajax: "{{ route('products.data') }}",
                columns: [
                    { data: 'id' },
                    { data: 'name' },
                    { data: 'sku' },
                    { data: 'price' },
                    { data: 'quantity' },
                    {
                        data: null,
                        render: function(data) {
                            return `
                                <button class="btn btn-sm btn-warning" onclick="editProduct(${data.id}, '${data.name}', '${data.sku}', ${data.price}, ${data.quantity})">Edit</button>
                                <button class="btn btn-sm btn-danger" onclick="deleteProduct(${data.id})">Delete</button>
                            `;
                        }
                    }
                ]
            });

            $('#productForm').submit(function (e) {
                e.preventDefault();
                $('.text-danger').remove();     // Remove old validation messages

                const id = $('#product_id').val();
                const url = id ? `/products/${id}` : `/products`;
                const method = id ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    type: method,
                    data: $(this).serialize(),
                    beforeSend: function () {
                        $('#save_product').prop('disabled', true).text('Saving...');
                    },
                    success: function () {
                        $('#productModal').modal('hide');
                        table.ajax.reload();
                        $('#productForm')[0].reset();
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function (key, value) {
                                // Only add error message if not already present
                                let input = $(`[name="${key}"]`);
                                input.next('.text-danger').remove();
                                input.after(`<div class="text-danger err">${value[0]}</div>`);
                            });
                        } else {
                        alert('Something went wrong. Try again.');
                        }
                    },
                    complete: function () {
                        $('#save_product').prop('disabled', false).text('Save Product');
                    },
                });
            });
        });

        function showAddModal() {
            $('#productForm')[0].reset();
            $('#product_id').val('');
            $('.err').text('');
            $('#productModal').modal('show');
        }

        function editProduct(id, name, sku, price, quantity) {
            $('.err').text('');
            $('#product_id').val(id);
            $('#name').val(name);
            $('#sku').val(sku);
            $('#price').val(price);
            $('#quantity').val(quantity);
            $('#productModal').modal('show');
        }

        function deleteProduct(id) {
            if (confirm('Delete this product?')) {
                $.ajax({
                    url: `/products/${id}`,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function () {
                        table.ajax.reload();
                    }
                });
            }
        }
    </script>
@endsection
