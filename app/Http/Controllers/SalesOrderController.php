<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreSalesOrderRequest;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\JsonResponse;

class SalesOrderController extends Controller
{
    /**
     * Display a listing of sales orders.
     */
    public function index()
    {
        return view('sales_orders.index');
    }

    /**
     * Show the form for creating a new sales order.
     */
    public function create()
    {
        $products = Product::all();
        return view('sales_orders.create', compact('products'));
    }

    public function getData()
    {
        $sales_order = SalesOrder::select(['id', 'customer_name', 'total', 'created_at']);
        return response()->json(['data' => $sales_order->get()]);
    }

    /**
     * Store a newly created sales order in the database.
     */
    public function store(StoreSalesOrderRequest $request)
    {
        $productsInput = $request->products;
        $quantities = $request->quantities;
        $prices = $request->prices;

        try {
            DB::transaction(function () use ($request, $productsInput, $quantities, $prices) {
                // Load all required products once
                $products = Product::whereIn('id', $productsInput)->get()->keyBy('id');

                // Validate stock before proceeding
                foreach ($productsInput as $index => $productId) {
                    // Retrieve product by ID. Assuming $products is a collection or array of product models.
                    $product = $products->find($productId); // Use `find()` for collections, or `[$productId]` for arrays.
                    
                    $requestedQty = $quantities[$index];

                    if (!$product) {
                        throw new \Exception("Product not found with ID: {$productId}");
                    }

                    // Check if the stock is sufficient
                    if ($product->quantity < $requestedQty) {
                        throw new \Exception("Insufficient stock for product: {$product->name}");
                    }
                }
                // Create the sales order
                $order = SalesOrder::create([
                    'customer_name' => $request->customer_name,
                    'total' => $request->total,
                ]);

                // Create items and reduce stock
                foreach ($productsInput as $index => $productId) {
                    $qty = $quantities[$index];
                    $price = $prices[$index];
                    $subtotal = $qty * $price;

                    SalesOrderItem::create([
                        'sales_order_id' => $order->id,
                        'product_id' => $productId,
                        'quantity' => $qty,
                        'price' => $price,
                        'subtotal' => $subtotal,
                    ]);

                    $products->get($productId)->decrement('quantity', $qty);
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!'
            ], 201);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order failed: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Display a specific sales order with items.
     */
    public function show(SalesOrder $sales_order)
    {
        $sales_order->load('items.product');
        return view('sales_orders.show', compact('sales_order'));
    }

    /**
     * Export a sales order as PDF.
     */
    public function exportPdf(SalesOrder $sales_order)
    {
        $sales_order->load('items.product');
        $pdf = Pdf::loadView('sales_orders.invoice', compact('sales_order'));
        return $pdf->download("sales_order_{$sales_order->id}.pdf");
    }
}
