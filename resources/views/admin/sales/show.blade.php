<x-admin-layout title="Sale #{{ $sale->id }}">
    <div class="max-w-2xl space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h1 class="text-2xl font-semibold text-slate-800 dark:text-slate-100">Sale #{{ $sale->id }}</h1>
            <div class="flex gap-2">
                <a href="{{ route('admin.sales.index') }}" class="px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors text-sm">Back</a>
                @if(auth()->user()->canAccess('sales.destroy') || auth()->user()->canAccess('sales.*'))
                <form action="{{ route('admin.sales.destroy', $sale) }}" method="POST" onsubmit="return confirm('Delete this sale?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 rounded-lg bg-danger/20 text-danger hover:bg-danger/30 transition-colors text-sm">Delete</button>
                </form>
                @endif
            </div>
        </div>

        <div class="rounded-xl bg-white dark:bg-slate-800 p-6 border border-slate-200 dark:border-slate-700 space-y-4">
            <p class="text-sm text-slate-500">Date: {{ $sale->created_at->format('M d, Y H:i') }}</p>
            <p class="text-sm text-slate-500">Cashier: {{ $sale->cashier?->name ?? '-' }}</p>
            <p class="text-sm text-slate-500">Shift: {{ $sale->shift ? $sale->shift->opened_at->format('M d, H:i') : '-' }}</p>

            <div class="pt-4 border-t border-slate-200 dark:border-slate-600">
                <h2 class="font-medium mb-2">Items</h2>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-slate-500">
                            <th class="pb-2">Name</th>
                            <th class="pb-2">Price</th>
                            <th class="pb-2">Qty</th>
                            <th class="pb-2 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-600">
                        @foreach($sale->items ?? [] as $item)
                            <tr>
                                <td class="py-2">{{ $item['name'] ?? '-' }}</td>
                                <td class="py-2">{{ number_format($item['price'] ?? 0, 2) }}</td>
                                <td class="py-2">{{ $item['quantity'] ?? 0 }} {{ $item['unit'] ?? '' }}</td>
                                <td class="py-2 text-right">{{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 0), 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <p class="text-lg font-semibold pt-4 border-t border-slate-200 dark:border-slate-600">
                Total: {{ number_format($sale->total_price, 2) }}
            </p>
        </div>
    </div>
</x-admin-layout>
