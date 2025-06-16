<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesOrder;
use App\Models\Product;
use Illuminate\Support\Carbon; 
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\StoreSalesOrderRequest;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = SalesOrder::count();
        $totalSales = SalesOrder::sum('total');
        $lowStock = Product::where('quantity', '<', 10)->get();

        $monthlySales = SalesOrder::selectRaw('SUM(total) as total, MONTH(created_at) as month')
        ->groupByRaw('MONTH(created_at)')
        ->pluck('total', 'month'); // returns [1 => 10000, 2 => 18000, ...]

        $labels = [];
        $data = [];

        for ($i = 1; $i <= 12; $i++) {
            $labels[] = Carbon::create()->month($i)->format('M'); // Jan, Feb, ...
            $data[] = $monthlySales[$i] ?? 0;
        }

        return view('dashboard', compact('totalOrders', 'totalSales', 'lowStock', 'labels', 'data'));
    }
}
