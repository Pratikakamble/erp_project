@extends('layouts.erp')
@section('title', 'Create Sales Order')
@section('content')
<h2 class="mb-2 fw-bold">Create Sales Order</h2>

<form id="orderForm">
    @csrf

    <div class="mb-3">
        <label>Customer Name</label>
        <input type="text" name="customer_name" id="customer_name" class="form-control">
        <div class="text-danger" id="error-customer_name"></div>
    </div>

    <table class="table table-bordered" id="orderItems">
        <thead>
            <tr>
                <th width="25%">Product</th><th width="25%">Price</th><th width="25%">Qty</th><th width="15%" >Subtotal</th><th width="10%" class="text-center"><button type="button" class="btn btn-sm btn-success" id="addRow">+</button></th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <div class="mb-3 text-end">
        <strong>Total: ₹ <span id="orderTotal">0.00</span></strong>
        <input type="hidden" name="total" id="totalInput">
    </div>

    <button type="submit" class="btn btn-primary" id="submitBtn">Submit Order</button>
</form>
@endsection

@section('scripts')
<script> 
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
const products = @json($products);

function getProductOptions() {
    return products.map(p => `<option value="${p.id}" data-price="${p.price}">${p.name}</option>`).join('');
}

function recalculateTotals() {
    let total = 0;
    $('#orderItems tbody tr').each(function () {
        const qty = parseInt($(this).find('.qty').val()) || 0;
        const price = parseFloat($(this).find('.price').val()) || 0;
        const subtotal = qty * price;
        $(this).find('.subtotal').text(subtotal.toFixed(2));
        total += subtotal;
    });
    $('#orderTotal').text(total.toFixed(2));
    $('#totalInput').val(total.toFixed(2));
}

function addRow() {
    const row = `
        <tr>
            <td>
                <select name="products[]" class="form-select product">
                <option value="">Select Product</option>
                ${getProductOptions()}</select>
                <div class="text-danger error-products"></div>
            </td>
            <td><input type="number" class="form-control price" name="prices[]" readonly></td>
            <td>
                <input type="number" class="form-control qty" name="quantities[]" value="1" min="1">
                <div class="text-danger error-quantities"></div>
            </td>
            <td class="text-right">₹ <span class="subtotal">0.00</span></td>
            <td class="text-center"><button type="button" class="btn btn-sm btn-danger removeRow">x</button></td>
        </tr>
    `;
    $('#orderItems tbody').append(row);
}

// Init first row
$(document).ready(function () {
    addRow();

    $(document).on('click', '#addRow', addRow);

    $(document).on('click', '.removeRow', function () {
        $(this).closest('tr').remove();
        recalculateTotals();
    });

    $(document).on('change', '.product', function () {
        const price = $(this).find(':selected').data('price') || 0;
        $(this).closest('tr').find('.price').val(price);
        recalculateTotals();
    });

    $(document).on('input', '.qty', function () {
        recalculateTotals();
    });

    $('#orderForm').submit(function (e) {
        e.preventDefault();

        // Clear previous errors
        $('.text-danger').html('');
        $('#submitBtn').prop('disabled', true).text('Saving...');

        $.ajax({
            url: "{{ route('sales-orders.store') }}",
            method: 'POST',
            data: $(this).serialize(),
            success: function (res) {
                if(res.success){
                    $('#orderForm')[0].reset();
                    $('#orderItems tbody').empty();
                    addRow();
                    $('#orderTotal').text('0.00');
                    alert(res.message);
                }else{
                    alert(res.message);
                }
            },
            error: function (xhr) {
                const resp = xhr.responseJSON;
                if (resp?.errors) {
                    $.each(resp.errors, function (field, messages) {
                        if (field.includes('.')) {
                            const [base, index] = field.split('.');
                            $(`.error-${base}`).eq(index).html(messages[0]);
                        } else {
                            $(`#error-${field}`).html(messages[0]);
                        }
                    });
                } else if (resp?.message) {
                    alert(resp.message);
                }else{
                    alert("Unexpected error occurred");
                }
            },

            complete: function () {
                $('#submitBtn').prop('disabled', false).text('Submit Order');
            }
        });
    });
});
</script>
@endsection
