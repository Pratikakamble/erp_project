<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesOrder;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = SalesOrder::count();
        $totalSales = SalesOrder::sum('total');
        $lowStock = Product::where('quantity', '<', 10)->get();

        return view('dashboard', compact('totalOrders', 'totalSales', 'lowStock'));
    }
}
