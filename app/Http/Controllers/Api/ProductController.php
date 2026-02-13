<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Product::query()
            ->with('category')
            ->when($request->boolean('active'), fn ($q) => $q->where('is_active', true))
            ->when($request->filled('category_id'), fn ($q) => $q->where('category_id', $request->category_id))
            ->when($request->filled('barcode'), fn ($q) => $q->where('barcode', $request->barcode))
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = mb_strtolower($request->search);
                $pattern = '%' . $search . '%';
                $q->where(function ($sub) use ($pattern) {
                    $sub->whereRaw('LOWER(name) LIKE ?', [$pattern])
                        ->orWhereRaw('LOWER(barcode) LIKE ?', [$pattern]);
                });
            })
            ->orderBy('name');

        $perPage = $request->integer('per_page', 0);
        if ($perPage > 0) {
            $paginator = $query->paginate(min($perPage, 100));
            return response()->json([
                'data' => $paginator->items(),
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
            ]);
        }

        return response()->json($query->get());
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $base = Str::slug($data['name']);
            $data['slug'] = $base ?: 'product-' . uniqid();
            while (Product::where('slug', $data['slug'])->exists()) {
                $data['slug'] = $base . '-' . Str::random(6);
            }
        }

        $product = Product::create($data);

        return response()->json($product->load('category'), 201);
    }

    public function show(Product $product): JsonResponse
    {
        return response()->json($product->load('category'));
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $product->update($request->validated());

        return response()->json($product->load('category'));
    }

    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return response()->json(null, 204);
    }
}
