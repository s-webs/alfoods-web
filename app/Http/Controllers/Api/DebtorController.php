<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Counterparty;
use App\Models\Sale;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DebtorController extends Controller
{
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
