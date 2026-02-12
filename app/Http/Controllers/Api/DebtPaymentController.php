<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DebtPayment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DebtPaymentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = DebtPayment::with(['sale', 'counterparty']);

        if ($request->has('sale_id')) {
            $query->where('sale_id', $request->sale_id);
        }

        if ($request->has('counterparty_id')) {
            $query->where('counterparty_id', $request->counterparty_id);
        }

        $payments = $query->orderByDesc('payment_date')->get();

        return response()->json($payments);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'sale_id' => ['required', 'integer', 'exists:sales,id'],
            'counterparty_id' => ['required', 'integer', 'exists:counterparties,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_date' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $sale = \App\Models\Sale::findOrFail($validated['sale_id']);

        if (!$sale->is_on_credit) {
            return response()->json(
                ['message' => 'Эта продажа не оформлена как продажа в долг.'],
                422
            );
        }

        $remainingDebt = $sale->remaining_debt;
        if ($validated['amount'] > $remainingDebt) {
            return response()->json(
                ['message' => "Сумма оплаты ({$validated['amount']}) превышает остаток долга ({$remainingDebt})."],
                422
            );
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($sale, $validated) {
            $payment = DebtPayment::create($validated);
            $sale->increment('paid_amount', $validated['amount']);
        });

        $payment = DebtPayment::with(['sale', 'counterparty'])
            ->where('sale_id', $validated['sale_id'])
            ->latest('payment_date')
            ->first();

        return response()->json($payment, 201);
    }
}
