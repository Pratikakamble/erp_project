<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSalesOrderRequest;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Gate;

class SalesOrderApiController extends Controller
{
    public function store(StoreSalesOrderRequest $request)
    {
        if (Gate::denies('is-admin') && Gate::denies('is-salesperson')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        $products = $request->products;
        $quantities = $request->quantities;

        if (!is_array($products) || !is_array($quantities)) {
            return response()->json([
                'success' => false,
                'message' => 'Products and quantities must be arrays.',
            ], 422);
        }

        $countProducts = count($products);
        $countQuantities = count($quantities);

        if ($countProducts === 0) {
            return response()->json([
                'success' => false,
                'message' => 'At least one product is required.',
            ], 422);
        }

        if ($countProducts !== $countQuantities) {
            return response()->json([
                'success' => false,
                'message' => 'Products and quantities must have the same number of items.',
            ], 422);
        }

        try {
            $order = DB::transaction(function () use ($request, $products, $quantities) {
                // Fetch products from DB
                $productModels = Product::whereIn('id', $products)->get()->keyBy('id');

                $total = 0;

                // Validate stock
                if(is_array($products)){
                foreach ($products as $index => $productId) {
                    $product = $productModels->get($productId);
                    $requestedQty = $quantities[$index] ?? 0;

                    if (!$product || $product->quantity < $requestedQty) {
                        throw ValidationException::withMessages([
                            "products.$index" => "Insufficient stock for " . ($product->name ?? 'Product'),
                        ]);
                    }

                    $total += $requestedQty * $product->price;
                }
                }

                // Create order
                $order = SalesOrder::create([
                    'customer_name' => $request->customer_name,
                    'total' => $total,
                ]);

                // Create order items and decrement stock
                if(is_array($products)){
                foreach ($products as $index => $productId) {
                    $product = $productModels->get($productId);
                    $qty = $quantities[$index];
                    $price = $product->price;
                    $subtotal = $qty * $price;

                    SalesOrderItem::create([
                        'sales_order_id' => $order->id,
                        'product_id' => $productId,
                        'quantity' => $qty,
                        'price' => $price,
                        'subtotal' => $subtotal,
                    ]);

                    $product->decrement('quantity', $qty);
                }
                }

                return $order;
            });

            return response()->json([
                'success' => true,
                'message' => 'Sales order created successfully.',
                'order_id' => $order->id,
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(SalesOrder $sales_order)
    {
        if (Gate::denies('is-admin') && Gate::denies('is-salesperson')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $sales_order->load('items.product');

        return response()->json([
            'id' => $sales_order->id,
            'customer_name' => $sales_order->customer_name,
            'total' => $sales_order->total,
            'created_at' => $sales_order->created_at->toDateTimeString(),
            'items' => $sales_order->items->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->subtotal,
                ];
            }),
        ]);
    }
}
