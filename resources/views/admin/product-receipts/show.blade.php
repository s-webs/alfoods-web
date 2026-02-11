<x-admin-layout title="Поступление #{{ $productReceipt->id }}">
    <div class="max-w-4xl space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h1 class="text-2xl font-semibold text-slate-800 dark:text-slate-100">Поступление #{{ $productReceipt->id }}</h1>
            <div class="flex gap-2">
                <a href="{{ route('admin.product-receipts.index') }}" class="px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors text-sm">Назад</a>
                @if(auth()->user()->canAccess('product-receipts.destroy') || auth()->user()->canAccess('product-receipts.*'))
                <form action="{{ route('admin.product-receipts.destroy', $productReceipt) }}" method="POST" onsubmit="return confirm('Удалить это поступление?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 rounded-lg bg-danger/20 text-danger hover:bg-danger/30 transition-colors text-sm">Удалить</button>
                </form>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-lg bg-accent/20 text-accent px-4 py-2 text-sm">{{ session('success') }}</div>
        @endif

        <form action="{{ route('admin.product-receipts.update', $productReceipt) }}" method="POST" x-data="receiptForm()" class="space-y-4 rounded-xl bg-white dark:bg-slate-800 p-6 border border-slate-200 dark:border-slate-700">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="counterparty_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Контрагент</label>
                    <select name="counterparty_id" id="counterparty_id" x-model="counterpartyId" @change="onCounterpartyChange()"
                            class="w-full rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-primary focus:border-primary">
                        <option value="">Выберите контрагента...</option>
                        @foreach($counterparties as $c)
                            <option value="{{ $c->id }}" {{ old('counterparty_id', $productReceipt->counterparty_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="supplier_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Или название поставщика</label>
                    <input type="text" name="supplier_name" id="supplier_name" x-model="supplierName" :disabled="counterpartyId" value="{{ old('supplier_name', $productReceipt->supplier_name) }}"
                           class="w-full rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-primary focus:border-primary"
                           placeholder="Введите название поставщика">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-200 dark:border-slate-600">
                <h2 class="font-medium mb-2">Товары</h2>
                <div class="rounded-lg border border-slate-200 dark:border-slate-600 overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-slate-50 dark:bg-slate-700/50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Товар</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Название</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Цена</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Кол-во</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Ед.</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-slate-500 uppercase">Сумма</th>
                                <th class="px-4 py-2 w-12"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(item, i) in items" :key="i">
                                <tr class="border-t border-slate-200 dark:border-slate-600">
                                    <td class="px-4 py-2">
                                        <select :name="'items[' + i + '][product_id]'" x-model="item.product_id" @change="onProductChange(i)" required
                                                class="w-full rounded border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm">
                                            <option value="0">Выбрать...</option>
                                            @foreach($products as $p)
                                                <option :value="{{ $p->id }}" :selected="item.product_id == {{ $p->id }}" data-name="{{ $p->name }}" data-price="{{ $p->purchase_price }}" data-unit="{{ $p->unit }}">{{ $p->name }}</option>
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
                                    <td class="px-4 py-2 text-right text-sm" x-text="(parseFloat(item.price) || 0) * (parseFloat(item.quantity) || 0)"></td>
                                    <td class="px-4 py-2">
                                        <button type="button" @click="removeItem(i)" class="text-danger hover:underline text-sm">Удалить</button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-between items-center mt-4">
                    <button type="button" @click="addItem()" class="text-primary hover:underline text-sm">+ Добавить товар</button>
                    <p class="text-sm">Итого: <span x-text="total.toFixed(2)" class="font-semibold"></span></p>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-200 dark:border-slate-600">
                <p class="text-sm text-slate-500">Дата: {{ $productReceipt->created_at->format('d.m.Y H:i') }}</p>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="px-4 py-2 rounded-lg bg-primary text-white hover:bg-primary-600 transition-colors">Сохранить изменения</button>
                <a href="{{ route('admin.product-receipts.index') }}" class="px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">Отмена</a>
            </div>
        </form>
    </div>

    @php
        $productsForJs = $products->map(fn($p) => [
            'id' => $p->id,
            'name' => $p->name,
            'price' => (float) $p->purchase_price,
            'unit' => $p->unit,
        ])->values();
        $itemsForJs = collect($productReceipt->items ?? [])->map(fn($item) => [
            'product_id' => $item['product_id'] ?? 0,
            'name' => $item['name'] ?? '',
            'price' => (float) ($item['price'] ?? 0),
            'quantity' => (float) ($item['quantity'] ?? 0),
            'unit' => $item['unit'] ?? 'pcs',
        ])->values();
    @endphp
    <script>
        function receiptForm() {
            const products = @json($productsForJs);
            const initialItems = @json($itemsForJs);
            return {
                counterpartyId: '{{ old('counterparty_id', $productReceipt->counterparty_id) }}',
                supplierName: '{{ old('supplier_name', $productReceipt->supplier_name ?? '') }}',
                items: initialItems.length > 0 ? initialItems : [{ product_id: '0', name: '', price: 0, quantity: 1, unit: 'pcs' }],
                onCounterpartyChange() {
                    if (this.counterpartyId) {
                        this.supplierName = '';
                    }
                },
                addItem() {
                    this.items.push({ product_id: '0', name: '', price: 0, quantity: 1, unit: 'pcs' });
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
