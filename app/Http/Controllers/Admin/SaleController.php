<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SaleController extends Controller
{
    public function index(): View
    {
        $sales = Sale::with(['cashier', 'shift'])
            ->orderByDesc('created_at')
            ->get();

        return view('admin.sales.index', compact('sales'));
    }

    public function create(): View
    {
        $products = Product::with('category')->where('is_active', true)->orderBy('name')->get();

        return view('admin.sales.create', compact('products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.name' => ['required', 'string', 'max:255'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.01'],
            'items.*.unit' => ['required', 'in:pcs,g'],
        ]);

        $items = $validated['items'];
        $totalPrice = 0;
        $totalQty = 0;

        foreach ($items as $item) {
            $totalPrice += (float) $item['price'] * (float) $item['quantity'];
            $totalQty += (float) $item['quantity'];
        }

        $sale = DB::transaction(function () use ($items, $totalPrice, $totalQty) {
            foreach ($items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $product->decrement('stock', $item['quantity']);
            }

            return Sale::create([
                'items' => $items,
                'total_qty' => (int) round($totalQty),
                'total_price' => round($totalPrice, 2),
            ]);
        });

        return redirect()->route('admin.sales.index')->with('success', 'Sale created.');
    }

    public function show(Sale $sale): View
    {
        $sale->load(['cashier', 'shift']);

        return view('admin.sales.show', compact('sale'));
    }

    public function destroy(Sale $sale): RedirectResponse
    {
        $sale->delete();

        return redirect()->route('admin.sales.index')->with('success', 'Sale deleted.');
    }
}
