<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCounterpartyRequest;
use App\Http\Requests\UpdateCounterpartyRequest;
use App\Models\Counterparty;
use Illuminate\Http\JsonResponse;

class CounterpartyController extends Controller
{
    public function index(): JsonResponse
    {
        $counterparties = Counterparty::orderBy('name')->get();

        return response()->json($counterparties);
    }

    public function store(StoreCounterpartyRequest $request): JsonResponse
    {
        $counterparty = Counterparty::create($request->validated());

        return response()->json($counterparty, 201);
    }

    public function show(Counterparty $counterparty): JsonResponse
    {
        return response()->json($counterparty);
    }

    public function update(UpdateCounterpartyRequest $request, Counterparty $counterparty): JsonResponse
    {
        $counterparty->update($request->validated());

        return response()->json($counterparty);
    }

    public function destroy(Counterparty $counterparty): JsonResponse
    {
        $counterparty->delete();

        return response()->json(null, 204);
    }
}
