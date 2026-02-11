<x-admin-layout title="Контрагент #{{ $counterparty->id }}">
    <div class="max-w-2xl space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h1 class="text-2xl font-semibold text-slate-800 dark:text-slate-100">{{ $counterparty->name }}</h1>
            <div class="flex gap-2">
                <a href="{{ route('admin.counterparties.index') }}" class="px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors text-sm">Назад</a>
                @if(auth()->user()->canAccess('counterparties.update') || auth()->user()->canAccess('counterparties.*'))
                <a href="{{ route('admin.counterparties.edit', $counterparty) }}" class="px-4 py-2 rounded-lg bg-primary text-white hover:bg-primary-600 transition-colors text-sm">Редактировать</a>
                @endif
            </div>
        </div>

        <div class="rounded-xl bg-white dark:bg-slate-800 p-6 border border-slate-200 dark:border-slate-700 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-slate-500">Название</p>
                    <p class="text-sm font-medium">{{ $counterparty->name }}</p>
                </div>
                @if($counterparty->iin)
                <div>
                    <p class="text-sm text-slate-500">ИИН</p>
                    <p class="text-sm">{{ $counterparty->iin }}</p>
                </div>
                @endif
                @if($counterparty->kbe)
                <div>
                    <p class="text-sm text-slate-500">КБЕ</p>
                    <p class="text-sm">{{ $counterparty->kbe }}</p>
                </div>
                @endif
                @if($counterparty->iik)
                <div>
                    <p class="text-sm text-slate-500">ИИК</p>
                    <p class="text-sm">{{ $counterparty->iik }}</p>
                </div>
                @endif
                @if($counterparty->bank_name)
                <div>
                    <p class="text-sm text-slate-500">Название банка</p>
                    <p class="text-sm">{{ $counterparty->bank_name }}</p>
                </div>
                @endif
                @if($counterparty->bik)
                <div>
                    <p class="text-sm text-slate-500">БИК</p>
                    <p class="text-sm">{{ $counterparty->bik }}</p>
                </div>
                @endif
                @if($counterparty->address)
                <div class="md:col-span-2">
                    <p class="text-sm text-slate-500">Адрес</p>
                    <p class="text-sm">{{ $counterparty->address }}</p>
                </div>
                @endif
                @if($counterparty->manager)
                <div>
                    <p class="text-sm text-slate-500">Руководитель</p>
                    <p class="text-sm">{{ $counterparty->manager }}</p>
                </div>
                @endif
                @if($counterparty->phone)
                <div>
                    <p class="text-sm text-slate-500">Телефон</p>
                    <p class="text-sm">{{ $counterparty->phone }}</p>
                </div>
                @endif
                @if($counterparty->email)
                <div>
                    <p class="text-sm text-slate-500">Email</p>
                    <p class="text-sm">{{ $counterparty->email }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
