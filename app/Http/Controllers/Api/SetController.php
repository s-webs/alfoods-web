<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSetRequest;
use App\Http\Requests\UpdateSetRequest;
use App\Models\ProductSet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SetController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $sets = ProductSet::query()
            ->with(['items.product'])
            ->when($request->boolean('active'), fn ($q) => $q->where('is_active', true))
            ->when($request->filled('barcode'), fn ($q) => $q->where('barcode', $request->barcode))
            ->orderBy('name')
            ->get();

        return response()->json($sets);
    }

    public function store(StoreSetRequest $request): JsonResponse
    {
        $data = $request->validated();
        $items = $data['items'];
        unset($data['items']);

        if (empty($data['slug'])) {
            $base = Str::slug($data['name']);
            $data['slug'] = $base ?: 'set-' . uniqid();
            while (ProductSet::where('slug', $data['slug'])->exists()) {
                $data['slug'] = $base . '-' . Str::random(6);
            }
        }

        $set = ProductSet::create($data);

        foreach ($items as $item) {
            $set->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
            ]);
        }

        return response()->json($set->load('items.product'), 201);
    }

    public function show(ProductSet $set): JsonResponse
    {
        return response()->json($set->load('items.product'));
    }

    public function update(UpdateSetRequest $request, ProductSet $set): JsonResponse
    {
        $data = $request->validated();

        if (isset($data['items'])) {
            $items = $data['items'];
            unset($data['items']);

            $set->items()->delete();
            foreach ($items as $item) {
                $set->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                ]);
            }
        }

        $set->update($data);

        return response()->json($set->load('items.product'));
    }

    public function destroy(ProductSet $set): JsonResponse
    {
        $set->delete();

        return response()->json(null, 204);
    }
}
