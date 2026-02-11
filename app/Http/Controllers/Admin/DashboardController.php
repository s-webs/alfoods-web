<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $stats = [
            'products' => Product::count(),
            'categories' => Category::count(),
            'sales_today' => Sale::whereDate('created_at', today())->count(),
            'revenue_today' => Sale::whereDate('created_at', today())->sum('total_price'),
        ];

        $recentSales = Sale::with(['cashier', 'shift'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentSales'));
    }
}
