<x-admin-layout title="Edit Product">
    <div class="max-w-2xl space-y-6">
        <h1 class="text-2xl font-semibold text-slate-800 dark:text-slate-100">Edit Product</h1>

        <form action="{{ route('admin.products.update', $product) }}" method="POST" class="space-y-4 rounded-xl bg-white dark:bg-slate-800 p-6 border border-slate-200 dark:border-slate-700">
            @csrf
            @method('PATCH')
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                       class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-primary focus:border-primary">
                @error('name')<p class="mt-1 text-sm text-danger">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="slug" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Slug</label>
                <input type="text" name="slug" id="slug" value="{{ old('slug', $product->slug) }}" required
                       class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-primary focus:border-primary">
                @error('slug')<p class="mt-1 text-sm text-danger">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="category_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Category</label>
                <select name="category_id" id="category_id" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-primary focus:border-primary">
                    <option value="">None</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" {{ old('category_id', $product->category_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="unit" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Unit</label>
                <select name="unit" id="unit" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-primary focus:border-primary">
                    <option value="pcs" {{ old('unit', $product->unit) == 'pcs' ? 'selected' : '' }}>Pieces</option>
                    <option value="g" {{ old('unit', $product->unit) == 'g' ? 'selected' : '' }}>Grams</option>
                </select>
            </div>
            <div>
                <label for="price" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Price</label>
                <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $product->price) }}"
                       class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-primary focus:border-primary">
            </div>
            <div>
                <label for="stock" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Stock</label>
                <input type="number" step="0.01" name="stock" id="stock" value="{{ old('stock', $product->stock) }}"
                       class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-primary focus:border-primary">
            </div>
            <div>
                <label for="stock_threshold" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Порог остатков</label>
                <input type="number" step="0.01" name="stock_threshold" id="stock_threshold" value="{{ old('stock_threshold', $product->stock_threshold ?? 0) }}"
                       class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-primary focus:border-primary">
            </div>
            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                       class="rounded border-slate-300 text-primary focus:ring-primary">
                <label for="is_active" class="ml-2 text-sm text-slate-700 dark:text-slate-300">Active</label>
            </div>
            <div class="flex gap-3 pt-4">
                <button type="submit" class="px-4 py-2 rounded-lg bg-primary text-white hover:bg-primary-600 transition-colors">Update</button>
                <a href="{{ route('admin.products.index') }}" class="px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">Cancel</a>
            </div>
        </form>
    </div>
</x-admin-layout>
