<x-admin-layout title="Cashiers">
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h1 class="text-2xl font-semibold text-slate-800 dark:text-slate-100">Cashiers</h1>
            @if(auth()->user()->canAccess('cashiers.create') || auth()->user()->canAccess('cashiers.*'))
            <a href="{{ route('admin.cashiers.create') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-primary text-white hover:bg-primary-600 transition-colors text-sm font-medium">
                Add Cashier
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Enabled</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                        @foreach($cashiers as $cashier)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30">
                                <td class="px-6 py-4 text-sm font-medium">{{ $cashier->name }}</td>
                                <td class="px-6 py-4 text-sm">{{ $cashier->user?->email ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm">{{ $cashier->enabled ? 'Yes' : 'No' }}</td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    @if(auth()->user()->canAccess('cashiers.update') || auth()->user()->canAccess('cashiers.*'))
                                    <a href="{{ route('admin.cashiers.edit', $cashier) }}" class="text-primary hover:underline text-sm">Edit</a>
                                    @endif
                                    @if(auth()->user()->canAccess('cashiers.destroy') || auth()->user()->canAccess('cashiers.*'))
                                    <form action="{{ route('admin.cashiers.destroy', $cashier) }}" method="POST" class="inline" onsubmit="return confirm('Delete this cashier?')">
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
