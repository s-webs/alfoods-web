<x-admin-layout title="Редактировать контрагента">
    <div class="max-w-2xl space-y-6">
        <h1 class="text-2xl font-semibold text-slate-800 dark:text-slate-100">Редактировать контрагента</h1>

        <form action="{{ route('admin.counterparties.update', $counterparty) }}" method="POST" class="space-y-4 rounded-xl bg-white dark:bg-slate-800 p-6 border border-slate-200 dark:border-slate-700">
            @csrf
            @method('PATCH')
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Название *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $counterparty->name) }}" required
                       class="w-full rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-primary focus:border-primary">
                @error('name')<p class="mt-1 text-sm text-danger">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="iin" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">ИИН</label>
                <input type="text" name="iin" id="iin" value="{{ old('iin', $counterparty->iin) }}"
                       class="w-full rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-primary focus:border-primary">
                @error('iin')<p class="mt-1 text-sm text-danger">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="kbe" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">КБЕ</label>
                <input type="text" name="kbe" id="kbe" value="{{ old('kbe', $counterparty->kbe) }}"
                       class="w-full rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-primary focus:border-primary">
                @error('kbe')<p class="mt-1 text-sm text-danger">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="iik" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">ИИК</label>
                <input type="text" name="iik" id="iik" value="{{ old('iik', $counterparty->iik) }}"
                       class="w-full rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-primary focus:border-primary">
                @error('iik')<p class="mt-1 text-sm text-danger">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="bank_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Название банка</label>
                <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name', $counterparty->bank_name) }}"
                       class="w-full rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-primary focus:border-primary">
                @error('bank_name')<p class="mt-1 text-sm text-danger">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="bik" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">БИК</label>
                <input type="text" name="bik" id="bik" value="{{ old('bik', $counterparty->bik) }}"
                       class="w-full rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-primary focus:border-primary">
                @error('bik')<p class="mt-1 text-sm text-danger">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="address" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Адрес</label>
                <textarea name="address" id="address" rows="3"
                       class="w-full rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-primary focus:border-primary">{{ old('address', $counterparty->address) }}</textarea>
                @error('address')<p class="mt-1 text-sm text-danger">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="manager" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Руководитель</label>
                <input type="text" name="manager" id="manager" value="{{ old('manager', $counterparty->manager) }}"
                       class="w-full rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-primary focus:border-primary">
                @error('manager')<p class="mt-1 text-sm text-danger">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Телефон</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $counterparty->phone) }}"
                       class="w-full rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-primary focus:border-primary">
                @error('phone')<p class="mt-1 text-sm text-danger">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $counterparty->email) }}"
                       class="w-full rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-primary focus:border-primary">
                @error('email')<p class="mt-1 text-sm text-danger">{{ $message }}</p>@enderror
            </div>
            <div class="flex gap-3 pt-4">
                <button type="submit" class="px-4 py-2 rounded-lg bg-primary text-white hover:bg-primary-600 transition-colors">Обновить</button>
                <a href="{{ route('admin.counterparties.index') }}" class="px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">Отмена</a>
            </div>
        </form>
    </div>
</x-admin-layout>
