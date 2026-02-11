<x-admin-layout title="Поступления товара">
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h1 class="text-2xl font-semibold text-slate-800 dark:text-slate-100">Поступления товара</h1>
            @if(auth()->user()->canAccess('product-receipts.create') || auth()->user()->canAccess('product-receipts.*'))
            <a href="{{ route('admin.product-receipts.create') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-primary text-white hover:bg-primary-600 transition-colors text-sm font-medium">
                Добавить поступление
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Дата</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Поставщик</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Товаров</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Сумма</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Действия</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                        @foreach($receipts as $receipt)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30">
                                <td class="px-6 py-4 text-sm">{{ $receipt->id }}</td>
                                <td class="px-6 py-4 text-sm">{{ $receipt->created_at->format('d.m.Y H:i') }}</td>
                                <td class="px-6 py-4 text-sm">{{ $receipt->counterparty?->name ?? $receipt->supplier_name ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm">{{ is_array($receipt->items) ? count($receipt->items) : 0 }}</td>
                                <td class="px-6 py-4 text-sm font-medium">{{ number_format($receipt->total_price, 2) }}</td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <a href="{{ route('admin.product-receipts.show', $receipt) }}" class="text-primary hover:underline text-sm">Просмотр</a>
                                    @if(auth()->user()->canAccess('product-receipts.destroy') || auth()->user()->canAccess('product-receipts.*'))
                                    <form action="{{ route('admin.product-receipts.destroy', $receipt) }}" method="POST" class="inline" onsubmit="return confirm('Удалить это поступление?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-danger hover:underline text-sm">Удалить</button>
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
