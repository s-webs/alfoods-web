<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCashierRequest;
use App\Http\Requests\UpdateCashierRequest;
use App\Models\Cashier;
use Illuminate\Http\JsonResponse;

class CashierController extends Controller
{
    public function index(): JsonResponse
    {
        $cashiers = Cashier::with('user')->get();

        return response()->json($cashiers);
    }

    public function store(StoreCashierRequest $request): JsonResponse
    {
        $cashier = Cashier::create($request->validated());

        return response()->json($cashier->load('user'), 201);
    }

    public function show(Cashier $cashier): JsonResponse
    {
        return response()->json($cashier->load('user'));
    }

    public function update(UpdateCashierRequest $request, Cashier $cashier): JsonResponse
    {
        $cashier->update($request->validated());

        return response()->json($cashier->load('user'));
    }

    public function destroy(Cashier $cashier): JsonResponse
    {
        $cashier->delete();

        return response()->json(null, 204);
    }
}
