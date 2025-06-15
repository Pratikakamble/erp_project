<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function index()
    {
        return view('products.index');
    }

    public function getData()
    {
        $products = Product::select(['id', 'name', 'sku', 'price', 'quantity']);
        return response()->json(['data' => $products->get()]);
    }

    public function store(ProductRequest $request)
    {
        Product::create($request->validated());
        return response()->json(['status'=> true, 'message' => 'Product Saved Successfully.']);
    }

    public function update(ProductRequest $request, Product $product)
    {
        $product->update($request->validated());
        return response()->json(['status'=> true, 'message' => 'Product Updated Successfully.']);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['status'=> true, 'message' => 'Product Deleted Successfully.']);
    }
}
