<x-admin-layout title="Counterparties">
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h1 class="text-2xl font-semibold text-slate-800 dark:text-slate-100">Контрагенты</h1>
            @if(auth()->user()->canAccess('counterparties.create') || auth()->user()->canAccess('counterparties.*'))
            <a href="{{ route('admin.counterparties.create') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-primary text-white hover:bg-primary-600 transition-colors text-sm font-medium">
                Добавить контрагента
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Название</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">ИИН</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Телефон</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Email</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Действия</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                        @foreach($counterparties as $counterparty)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30">
                                <td class="px-6 py-4 text-sm font-medium">{{ $counterparty->name }}</td>
                                <td class="px-6 py-4 text-sm text-slate-500">{{ $counterparty->iin ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-slate-500">{{ $counterparty->phone ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-slate-500">{{ $counterparty->email ?? '-' }}</td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    @if(auth()->user()->canAccess('counterparties.update') || auth()->user()->canAccess('counterparties.*'))
                                    <a href="{{ route('admin.counterparties.edit', $counterparty) }}" class="text-primary hover:underline text-sm">Редактировать</a>
                                    @endif
                                    @if(auth()->user()->canAccess('counterparties.destroy') || auth()->user()->canAccess('counterparties.*'))
                                    <form action="{{ route('admin.counterparties.destroy', $counterparty) }}" method="POST" class="inline" onsubmit="return confirm('Удалить этого контрагента?')">
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
