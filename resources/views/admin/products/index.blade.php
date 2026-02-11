<x-admin-layout title="Products">
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h1 class="text-2xl font-semibold text-slate-800 dark:text-slate-100">Products</h1>
            @if(auth()->user()->canAccess('products.create') || auth()->user()->canAccess('products.*'))
            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-primary text-white hover:bg-primary-600 transition-colors text-sm font-medium">
                Add Product
            </a>
            @endif
        </div>

        @if(session('success'))
            <div class="rounded-lg bg-accent/20 text-accent px-4 py-2 text-sm">{{ session('success') }}</div>
        @endif

        <form method="GET" class="flex gap-2">
            <select name="category_id" onchange="this.form.submit()" class="rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white">
                <option value="">All Categories</option>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}" {{ request('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
        </form>

        <div class="rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 dark:bg-slate-700/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Unit</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                        @foreach($products as $product)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30">
                                <td class="px-6 py-4 text-sm font-medium">{{ $product->name }}</td>
                                <td class="px-6 py-4 text-sm">{{ $product->category?->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm">{{ number_format($product->price, 2) }}</td>
                                <td class="px-6 py-4 text-sm">{{ $product->stock }}</td>
                                <td class="px-6 py-4 text-sm">{{ $product->unit }}</td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    @if(auth()->user()->canAccess('products.update') || auth()->user()->canAccess('products.*'))
                                    <a href="{{ route('admin.products.edit', $product) }}" class="text-primary hover:underline text-sm">Edit</a>
                                    @endif
                                    @if(auth()->user()->canAccess('products.destroy') || auth()->user()->canAccess('products.*'))
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Delete this product?')">
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
