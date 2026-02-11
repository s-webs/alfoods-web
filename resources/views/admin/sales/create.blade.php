<x-admin-layout title="New Sale">
    <div class="max-w-4xl space-y-6">
        <h1 class="text-2xl font-semibold text-slate-800 dark:text-slate-100">New Sale</h1>

        <form action="{{ route('admin.sales.store') }}" method="POST" x-data="saleForm()" class="space-y-4 rounded-xl bg-white dark:bg-slate-800 p-6 border border-slate-200 dark:border-slate-700">
            @csrf
            <div class="rounded-lg border border-slate-200 dark:border-slate-600 overflow-hidden">
                <table class="w-full">
                    <thead class="bg-slate-50 dark:bg-slate-700/50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Product</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Name</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Price</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Qty</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Unit</th>
                            <th class="px-4 py-2 w-12"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(item, i) in items" :key="i">
                            <tr class="border-t border-slate-200 dark:border-slate-600">
                                <td class="px-4 py-2">
                                    <select :name="'items[' + i + '][product_id]'" x-model="item.product_id" @change="onProductChange(i)" required
                                            class="w-full rounded border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm">
                                        <option value="">Select...</option>
                                        @foreach($products as $p)
                                            <option value="{{ $p->id }}" data-name="{{ $p->name }}" data-price="{{ $p->price }}" data-unit="{{ $p->unit }}">{{ $p->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-4 py-2">
                                    <input type="text" :name="'items[' + i + '][name]'" x-model="item.name" required
                                           class="w-full rounded border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm">
                                </td>
                                <td class="px-4 py-2">
                                    <input type="number" step="0.01" :name="'items[' + i + '][price]'" x-model="item.price" required
                                           class="w-24 rounded border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm">
                                </td>
                                <td class="px-4 py-2">
                                    <input type="number" :step="item.unit === 'pcs' ? 1 : 0.01" :name="'items[' + i + '][quantity]'" x-model="item.quantity" required :min="item.unit === 'pcs' ? 1 : 0.01"
                                           class="w-20 rounded border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm">
                                </td>
                                <td class="px-4 py-2">
                                    <input type="hidden" :name="'items[' + i + '][unit]'" x-model="item.unit">
                                    <span x-text="item.unit" class="text-sm"></span>
                                </td>
                                <td class="px-4 py-2">
                                    <button type="button" @click="removeItem(i)" class="text-danger hover:underline text-sm">Remove</button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-between items-center">
                <button type="button" @click="addItem()" class="text-primary hover:underline text-sm">+ Add item</button>
                <p class="text-sm">Total: <span x-text="total.toFixed(2)" class="font-semibold"></span></p>
            </div>
            <div class="flex gap-3 pt-4">
                <button type="submit" class="px-4 py-2 rounded-lg bg-primary text-white hover:bg-primary-600 transition-colors">Create Sale</button>
                <a href="{{ route('admin.sales.index') }}" class="px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">Cancel</a>
            </div>
        </form>
    </div>

    @php
        $productsForJs = $products->map(fn($p) => [
            'id' => $p->id,
            'name' => $p->name,
            'price' => (float) $p->price,
            'unit' => $p->unit,
        ])->values();
    @endphp
    <script>
        function saleForm() {
            const products = @json($productsForJs);
            return {
                items: [{ product_id: '', name: '', price: 0, quantity: 1, unit: 'pcs' }],
                addItem() {
                    this.items.push({ product_id: '', name: '', price: 0, quantity: 1, unit: 'pcs' });
                },
                removeItem(i) {
                    this.items.splice(i, 1);
                },
                onProductChange(i) {
                    const id = parseInt(this.items[i].product_id);
                    const p = products.find(x => x.id === id);
                    if (p) {
                        this.items[i].name = p.name;
                        this.items[i].price = p.price;
                        this.items[i].unit = p.unit;
                        this.items[i].quantity = 1;
                    }
                },
                get total() {
                    return this.items.reduce((s, i) => s + (parseFloat(i.price) || 0) * (parseFloat(i.quantity) || 0), 0);
                }
            }
        }
    </script>
</x-admin-layout>
