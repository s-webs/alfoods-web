<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Counterparty;
use App\Models\DebtPayment;
use App\Models\Sale;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DebtorController extends Controller
{
    /**
     * Общая оплата долгов по контрагенту: сумма распределяется по неоплаченным продажам,
     * начиная с самых старых.
     */
    public function payDebtBulk(Counterparty $counterparty, Request $request): JsonResponse
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_date' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $sales = Sale::where('counterparty_id', $counterparty->id)
            ->where('is_on_credit', true)
            ->with('debtPayments')
            ->orderBy('created_at', 'asc')
            ->get();

        $unpaidSales = $sales->filter(function (Sale $sale) {
            return $sale->remaining_debt > 0;
        })->values();

        if ($unpaidSales->isEmpty()) {
            return response()->json(
                ['message' => 'У контрагента нет неоплаченных продаж в долг.'],
                422
            );
        }

        $totalDebt = $unpaidSales->sum(fn (Sale $s) => $s->remaining_debt);
        $amount = (float) $validated['amount'];
        if ($amount > $totalDebt) {
            return response()->json(
                ['message' => "Сумма оплаты ({$amount}) превышает общий остаток долга ({$totalDebt})."],
                422
            );
        }

        $created = [];
        $remaining = $amount;

        DB::transaction(function () use ($unpaidSales, $counterparty, $validated, &$created, &$remaining) {
            foreach ($unpaidSales as $sale) {
                if ($remaining <= 0) {
                    break;
                }
                $debt = $sale->remaining_debt;
                $pay = min($debt, $remaining);
                if ($pay <= 0) {
                    continue;
                }

                DebtPayment::create([
                    'sale_id' => $sale->id,
                    'counterparty_id' => $counterparty->id,
                    'amount' => $pay,
                    'payment_date' => $validated['payment_date'],
                    'notes' => $validated['notes'] ?? null,
                ]);

                $sale->increment('paid_amount', $pay);
                $remaining -= $pay;
                $created[] = [
                    'sale_id' => $sale->id,
                    'amount' => round($pay, 2),
                ];
            }
        });

        return response()->json([
            'message' => 'Оплата зарегистрирована',
            'total_paid' => round($amount, 2),
            'payments' => $created,
        ]);
    }

    public function index(): JsonResponse
    {
        $debtors = Counterparty::with(['sales' => function ($query) {
            $query->where('is_on_credit', true)
                ->orderByDesc('created_at');
        }])
            ->whereHas('sales', function ($query) {
                $query->where('is_on_credit', true);
            })
            ->get()
            ->map(function ($counterparty) {
                $creditSales = $counterparty->sales->where('is_on_credit', true);
                
                $totalDebt = 0;
                $unpaidSales = [];
                
                foreach ($creditSales as $sale) {
                    // Load debt payments to calculate remaining debt correctly
                    $sale->load('debtPayments');
                    $remainingDebt = $sale->remaining_debt;
                    if ($remainingDebt > 0) {
                        $totalDebt += $remainingDebt;
                        $unpaidSales[] = [
                            'id' => $sale->id,
                            'total_price' => $sale->total_price,
                            'paid_amount' => $sale->paid_amount,
                            'remaining_debt' => $remainingDebt,
                            'created_at' => $sale->created_at->toDateTimeString(),
                            'items' => $sale->items ?? [],
                        ];
                    }
                }

                return [
                    'id' => $counterparty->id,
                    'name' => $counterparty->name,
                    'iin' => $counterparty->iin,
                    'address' => $counterparty->address,
                    'phone' => $counterparty->phone,
                    'total_debt' => round($totalDebt, 2),
                    'unpaid_sales_count' => count($unpaidSales),
                    'last_sale_date' => $creditSales->isNotEmpty() 
                        ? $creditSales->first()->created_at->toDateTimeString() 
                        : null,
                    'unpaid_sales' => $unpaidSales,
                ];
            })
            ->filter(function ($debtor) {
                return $debtor['total_debt'] > 0;
            })
            ->sortByDesc('total_debt')
            ->values();

        return response()->json($debtors);
    }
}
