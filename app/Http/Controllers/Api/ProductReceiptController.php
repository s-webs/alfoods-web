<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductReceiptRequest;
use App\Http\Requests\UpdateProductReceiptRequest;
use App\Models\Product;
use App\Models\ProductReceipt;
use App\Models\ProductSet;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ProductReceiptController extends Controller
{
    public function index(): JsonResponse
    {
        $receipts = ProductReceipt::with('counterparty')->orderByDesc('created_at')->get();

        return response()->json($receipts);
    }

    public function store(StoreProductReceiptRequest $request): JsonResponse
    {
        $data = $request->validated();
        $items = $data['items'];

        $totalPrice = 0;
        $totalQty = 0;

        foreach ($items as $item) {
            $totalPrice += (float) $item['price'] * (float) $item['quantity'];
            $totalQty += (float) $item['quantity'];
        }

        $receipt = DB::transaction(function () use ($data, $items, $totalPrice, $totalQty) {
            foreach ($items as $item) {
                $this->incrementStockForItem($item);
                $this->updatePurchasePriceForItem($item);
            }

            return ProductReceipt::create([
                'counterparty_id' => $data['counterparty_id'] ?? null,
                'supplier_name' => $data['supplier_name'] ?? null,
                'items' => $items,
                'total_qty' => (int) round($totalQty),
                'total_price' => round($totalPrice, 2),
            ]);
        });

        return response()->json($receipt->load('counterparty'), 201);
    }

    public function show(ProductReceipt $productReceipt): JsonResponse
    {
        return response()->json($productReceipt->load('counterparty'));
    }

    public function update(UpdateProductReceiptRequest $request, ProductReceipt $productReceipt): JsonResponse
    {
        $data = $request->validated();

        if (isset($data['items'])) {
            $newItems = $data['items'];
            $totalPrice = 0;
            $totalQty = 0;
            foreach ($newItems as $item) {
                $totalPrice += (float) $item['price'] * (float) $item['quantity'];
                $totalQty += (float) $item['quantity'];
            }
            $data['total_qty'] = (int) round($totalQty);
            $data['total_price'] = round($totalPrice, 2);

            DB::transaction(function () use ($productReceipt, $data, $newItems) {
                $oldItems = $productReceipt->items ?? [];
                foreach ($oldItems as $item) {
                    $this->decrementStockForItem($item);
                }
                foreach ($newItems as $item) {
                    $this->incrementStockForItem($item);
                    $this->updatePurchasePriceForItem($item);
                }
                $productReceipt->update($data);
            });
        } else {
            $productReceipt->update($data);
        }

        return response()->json($productReceipt->load('counterparty'));
    }

    public function destroy(ProductReceipt $productReceipt): JsonResponse
    {
        DB::transaction(function () use ($productReceipt) {
            $items = $productReceipt->items ?? [];
            foreach ($items as $item) {
                $this->decrementStockForItem($item);
            }
            $productReceipt->delete();
        });

        return response()->json(null, 204);
    }

    private function incrementStockForItem(array $item): void
    {
        $setId = isset($item['set_id']) ? (int) $item['set_id'] : 0;
        $productId = (int) ($item['product_id'] ?? 0);
        $qty = (float) ($item['quantity'] ?? 0);

        if ($setId > 0) {
            $set = ProductSet::with('items')->find($setId);
            if ($set) {
                foreach ($set->items as $setItem) {
                    $product = Product::find($setItem->product_id);
                    if ($product) {
                        $product->increment('stock', $qty * $setItem->quantity);
                    }
                }
            }
        } elseif ($productId > 0) {
            $product = Product::find($productId);
            if ($product) {
                $product->increment('stock', $qty);
            }
        }
    }

    private function decrementStockForItem(array $item): void
    {
        $setId = isset($item['set_id']) ? (int) $item['set_id'] : 0;
        $productId = (int) ($item['product_id'] ?? 0);
        $qty = (float) ($item['quantity'] ?? 0);

        if ($setId > 0) {
            $set = ProductSet::with('items')->find($setId);
            if ($set) {
                foreach ($set->items as $setItem) {
                    $product = Product::find($setItem->product_id);
                    if ($product) {
                        $product->decrement('stock', $qty * $setItem->quantity);
                    }
                }
            }
        } elseif ($productId > 0) {
            $product = Product::find($productId);
            if ($product) {
                $product->decrement('stock', $qty);
            }
        }
    }

    private function updatePurchasePriceForItem(array $item): void
    {
        $productId = (int) ($item['product_id'] ?? 0);
        $price = (float) ($item['price'] ?? 0);

        if ($productId > 0 && $price > 0) {
            $product = Product::find($productId);
            if ($product) {
                $product->update(['purchase_price' => $price]);
            }
        }
    }
}
