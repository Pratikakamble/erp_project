<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use illuminate\Support\Facades\Gate;

class ProductApiController extends Controller
{
    public function index()
    {
        if (Gate::denies('is-admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        $products = Product::select('id', 'name', 'sku', 'price', 'quantity')->get();
        return response()->json($products);
    }
}
