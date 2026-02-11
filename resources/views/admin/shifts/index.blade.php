<x-admin-layout title="Shifts">
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h1 class="text-2xl font-semibold text-slate-800 dark:text-slate-100">Shifts</h1>
            @if(auth()->user()->canAccess('shifts.create') || auth()->user()->canAccess('shifts.*'))
            <a href="{{ route('admin.shifts.create') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-primary text-white hover:bg-primary-600 transition-colors text-sm font-medium">
                Add Shift
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Opened</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Closed</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                        @foreach($shifts as $shift)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30">
                                <td class="px-6 py-4 text-sm">{{ $shift->opened_at?->format('M d, Y H:i') }}</td>
                                <td class="px-6 py-4 text-sm">{{ $shift->closed_at?->format('M d, Y H:i') ?? '-' }}</td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    @if(auth()->user()->canAccess('shifts.update') || auth()->user()->canAccess('shifts.*'))
                                    <a href="{{ route('admin.shifts.edit', $shift) }}" class="text-primary hover:underline text-sm">Edit</a>
                                    @endif
                                    @if(auth()->user()->canAccess('shifts.destroy') || auth()->user()->canAccess('shifts.*'))
                                    <form action="{{ route('admin.shifts.destroy', $shift) }}" method="POST" class="inline" onsubmit="return confirm('Delete this shift?')">
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
