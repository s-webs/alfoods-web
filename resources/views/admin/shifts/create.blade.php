<x-admin-layout title="Add Shift">
    <div class="max-w-2xl space-y-6">
        <h1 class="text-2xl font-semibold text-slate-800 dark:text-slate-100">Add Shift</h1>

        <form action="{{ route('admin.shifts.store') }}" method="POST" class="space-y-4 rounded-xl bg-white dark:bg-slate-800 p-6 border border-slate-200 dark:border-slate-700">
            @csrf
            <div>
                <label for="opened_at" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Opened At</label>
                <input type="datetime-local" name="opened_at" id="opened_at" value="{{ old('opened_at', now()->format('Y-m-d\TH:i')) }}" required
                       class="w-full rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-primary focus:border-primary">
                @error('opened_at')<p class="mt-1 text-sm text-danger">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="closed_at" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Closed At</label>
                <input type="datetime-local" name="closed_at" id="closed_at" value="{{ old('closed_at') }}"
                       class="w-full rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-primary focus:border-primary">
                @error('closed_at')<p class="mt-1 text-sm text-danger">{{ $message }}</p>@enderror
            </div>
            <div class="flex gap-3 pt-4">
                <button type="submit" class="px-4 py-2 rounded-lg bg-primary text-white hover:bg-primary-600 transition-colors">Create</button>
                <a href="{{ route('admin.shifts.index') }}" class="px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">Cancel</a>
            </div>
        </form>
    </div>
</x-admin-layout>
