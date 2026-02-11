<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReturnRequest;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use App\Models\Product;
use App\Models\ProductSet;
use App\Models\Sale;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SaleController extends Controller
{
    public function index(): JsonResponse
    {
        $sales = Sale::with(['cashier', 'shift'])->orderByDesc('created_at')->get();

        return response()->json($sales);
    }

    public function store(StoreSaleRequest $request): JsonResponse
    {
        $data = $request->validated();
        $items = $data['items'];

        foreach ($items as $index => $item) {
            $setId = isset($item['set_id']) ? (int) $item['set_id'] : 0;
            $productId = (int) $item['product_id'];
            if ($productId > 0 && $setId === 0) {
                $product = Product::find($productId);
                if ($product && $product->unit !== $item['unit']) {
                    throw ValidationException::withMessages([
                        "items.{$index}.unit" => ["Product unit is '{$product->unit}', cannot use '{$item['unit']}'."],
                    ]);
                }
            }
        }

        $totalPrice = 0;
        $totalQty = 0;

        foreach ($items as $item) {
            $totalPrice += (float) $item['price'] * (float) $item['quantity'];
            $totalQty += (float) $item['quantity'];
        }

        $sale = DB::transaction(function () use ($data, $items, $totalPrice, $totalQty) {
            foreach ($items as $item) {
                $this->decrementStockForItem($item);
            }

            return Sale::create([
                'cashier_id' => $data['cashier_id'] ?? null,
                'shift_id' => $data['shift_id'] ?? null,
                'shopper_id' => $data['shopper_id'] ?? null,
                'items' => $items,
                'total_qty' => (int) round($totalQty),
                'total_price' => round($totalPrice, 2),
                'status' => Sale::STATUS_COMPLETED,
            ]);
        });

        return response()->json($sale->load(['cashier', 'shift']), 201);
    }

    public function show(Sale $sale): JsonResponse
    {
        return response()->json($sale->load(['cashier', 'shift']));
    }

    public function update(UpdateSaleRequest $request, Sale $sale): JsonResponse
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

            DB::transaction(function () use ($sale, $data, $newItems) {
                $oldItems = $sale->items ?? [];
                foreach ($oldItems as $item) {
                    $this->incrementStockForItem($item);
                }
                foreach ($newItems as $item) {
                    $this->decrementStockForItem($item);
                }
                $sale->update($data);
            });
        } else {
            $sale->update($data);
        }

        return response()->json($sale->load(['cashier', 'shift']));
    }

    public function storeReturn(StoreReturnRequest $request): JsonResponse
    {
        $data = $request->validated();
        $items = $data['items'];

        $totalPrice = 0;
        $totalQty = 0;
        foreach ($items as $item) {
            $totalPrice += (float) $item['price'] * (float) $item['quantity'];
            $totalQty += (float) $item['quantity'];
        }

        $sale = DB::transaction(function () use ($data, $items, $totalPrice, $totalQty) {
            foreach ($items as $item) {
                $this->incrementStockForItem($item);
            }

            return Sale::create([
                'cashier_id' => $data['cashier_id'] ?? null,
                'shift_id' => $data['shift_id'] ?? null,
                'shopper_id' => null,
                'items' => $items,
                'total_qty' => (int) round($totalQty),
                'total_price' => round($totalPrice, 2),
                'status' => Sale::STATUS_RETURNED,
            ]);
        });

        return response()->json($sale->load(['cashier', 'shift']), 201);
    }

    public function returnSale(Sale $sale): JsonResponse
    {
        if ($sale->status === Sale::STATUS_RETURNED) {
            return response()->json(
                ['message' => 'Продажа уже оформлена как возврат.'],
                422
            );
        }

        DB::transaction(function () use ($sale) {
            $items = $sale->items ?? [];
            foreach ($items as $item) {
                $this->incrementStockForItem($item);
            }
            $sale->update(['status' => Sale::STATUS_RETURNED]);
        });

        return response()->json($sale->fresh()->load(['cashier', 'shift']));
    }

    public function destroy(Sale $sale): JsonResponse
    {
        DB::transaction(function () use ($sale) {
            $items = $sale->items ?? [];
            foreach ($items as $item) {
                $this->incrementStockForItem($item);
            }
            $sale->delete();
        });

        return response()->json(null, 204);
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
}
