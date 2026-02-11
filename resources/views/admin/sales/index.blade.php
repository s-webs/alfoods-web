<x-admin-layout title="Sales">
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h1 class="text-2xl font-semibold text-slate-800 dark:text-slate-100">Sales</h1>
            @if(auth()->user()->canAccess('sales.create') || auth()->user()->canAccess('sales.*'))
            <a href="{{ route('admin.sales.create') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-primary text-white hover:bg-primary-600 transition-colors text-sm font-medium">
                New Sale
            </a>
            @endif
        </div>

        @if(session('success'))
            <div class="rounded-lg bg-accent/20 text-accent px-4 py-2 text-sm">{{ session('success') }}</div>
        @endif

        <div class="rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 dark:bg-slate-700/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Items</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Cashier</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                        @foreach($sales as $sale)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30">
                                <td class="px-6 py-4 text-sm">{{ $sale->id }}</td>
                                <td class="px-6 py-4 text-sm">{{ $sale->created_at->format('M d, Y H:i') }}</td>
                                <td class="px-6 py-4 text-sm">{{ is_array($sale->items) ? count($sale->items) : 0 }}</td>
                                <td class="px-6 py-4 text-sm font-medium">{{ number_format($sale->total_price, 2) }}</td>
                                <td class="px-6 py-4 text-sm">{{ $sale->cashier?->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <a href="{{ route('admin.sales.show', $sale) }}" class="text-primary hover:underline text-sm">View</a>
                                    @if(auth()->user()->canAccess('sales.destroy') || auth()->user()->canAccess('sales.*'))
                                    <form action="{{ route('admin.sales.destroy', $sale) }}" method="POST" class="inline" onsubmit="return confirm('Delete this sale?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-danger hover:underline text-sm">Delete</button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
