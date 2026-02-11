<x-admin-layout title="Categories">
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h1 class="text-2xl font-semibold text-slate-800 dark:text-slate-100">Categories</h1>
            @if(auth()->user()->canAccess('categories.create') || auth()->user()->canAccess('categories.*'))
            <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-primary text-white hover:bg-primary-600 transition-colors text-sm font-medium">
                Add Category
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Slug</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Active</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                        @foreach($categories as $category)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30">
                                <td class="px-6 py-4 text-sm font-medium">{{ $category->name }}</td>
                                <td class="px-6 py-4 text-sm text-slate-500">{{ $category->slug }}</td>
                                <td class="px-6 py-4 text-sm">
                                    @if($category->is_active)
                                        <span class="text-accent">Yes</span>
                                    @else
                                        <span class="text-slate-400">No</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    @if(auth()->user()->canAccess('categories.update') || auth()->user()->canAccess('categories.*'))
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="text-primary hover:underline text-sm">Edit</a>
                                    @endif
                                    @if(auth()->user()->canAccess('categories.destroy') || auth()->user()->canAccess('categories.*'))
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Delete this category?')">
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
