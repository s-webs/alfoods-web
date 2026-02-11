<x-admin-layout title="Edit Cashier">
    <div class="max-w-2xl space-y-6">
        <h1 class="text-2xl font-semibold text-slate-800 dark:text-slate-100">Edit Cashier</h1>

        <form action="{{ route('admin.cashiers.update', $cashier) }}" method="POST" class="space-y-4 rounded-xl bg-white dark:bg-slate-800 p-6 border border-slate-200 dark:border-slate-700">
            @csrf
            @method('PATCH')
            <div>
                <label for="user_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">User</label>
                <select name="user_id" id="user_id" required class="w-full rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-primary focus:border-primary">
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ old('user_id', $cashier->user_id) == $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ $u->email }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $cashier->name) }}" required
                       class="w-full rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-primary focus:border-primary">
                @error('name')<p class="mt-1 text-sm text-danger">{{ $message }}</p>@enderror
            </div>
            <div class="flex items-center">
                <input type="checkbox" name="enabled" id="enabled" value="1" {{ old('enabled', $cashier->enabled) ? 'checked' : '' }}
                       class="rounded border-slate-300 text-primary focus:ring-primary">
                <label for="enabled" class="ml-2 text-sm text-slate-700 dark:text-slate-300">Enabled</label>
            </div>
            <div class="flex gap-3 pt-4">
                <button type="submit" class="px-4 py-2 rounded-lg bg-primary text-white hover:bg-primary-600 transition-colors">Update</button>
                <a href="{{ route('admin.cashiers.index') }}" class="px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">Cancel</a>
            </div>
        </form>
    </div>
</x-admin-layout>
