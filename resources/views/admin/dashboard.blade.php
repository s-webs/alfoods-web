<x-admin-layout title="Dashboard">
    <div class="space-y-6">
        <h1 class="text-2xl font-semibold text-slate-800 dark:text-slate-100">Dashboard</h1>

        @if(session('success'))
            <div class="rounded-lg bg-accent/20 text-accent px-4 py-2 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="rounded-xl bg-white dark:bg-slate-800 p-6 border border-slate-200 dark:border-slate-700">
                <p class="text-sm text-slate-500 dark:text-slate-400">Products</p>
                <p class="text-2xl font-bold text-primary">{{ $stats['products'] }}</p>
            </div>
            <div class="rounded-xl bg-white dark:bg-slate-800 p-6 border border-slate-200 dark:border-slate-700">
                <p class="text-sm text-slate-500 dark:text-slate-400">Categories</p>
                <p class="text-2xl font-bold text-primary">{{ $stats['categories'] }}</p>
            </div>
            <div class="rounded-xl bg-white dark:bg-slate-800 p-6 border border-slate-200 dark:border-slate-700">
                <p class="text-sm text-slate-500 dark:text-slate-400">Sales Today</p>
                <p class="text-2xl font-bold text-accent">{{ $stats['sales_today'] }}</p>
            </div>
            <div class="rounded-xl bg-white dark:bg-slate-800 p-6 border border-slate-200 dark:border-slate-700">
                <p class="text-sm text-slate-500 dark:text-slate-400">Revenue Today</p>
                <p class="text-2xl font-bold text-accent">{{ number_format($stats['revenue_today'], 2) }}</p>
            </div>
        </div>

        <div class="rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                <h2 class="font-semibold text-slate-800 dark:text-slate-100">Recent Sales</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 dark:bg-slate-700/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Cashier</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                        @forelse($recentSales as $sale)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30">
                                <td class="px-6 py-4 text-sm">{{ $sale->id }}</td>
                                <td class="px-6 py-4 text-sm">{{ $sale->created_at->format('M d, Y H:i') }}</td>
                                <td class="px-6 py-4 text-sm font-medium">{{ number_format($sale->total_price, 2) }}</td>
                                <td class="px-6 py-4 text-sm">{{ $sale->cashier?->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.sales.show', $sale) }}" class="text-primary hover:underline text-sm">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-500">No sales yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
