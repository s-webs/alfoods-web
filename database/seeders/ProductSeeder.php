<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all()->keyBy('id');
        $categoryIds = $categories->pluck('id')->toArray();

        $products = [
            // Овощи и фрукты (граммовые)
            ['name' => 'Помидоры', 'unit' => 'g', 'price' => 85, 'category' => 1],
            ['name' => 'Огурцы', 'unit' => 'g', 'price' => 75, 'category' => 1],
            ['name' => 'Картофель', 'unit' => 'g', 'price' => 45, 'category' => 1],
            ['name' => 'Яблоки Голден', 'unit' => 'g', 'price' => 120, 'category' => 1],
            ['name' => 'Бананы', 'unit' => 'g', 'price' => 95, 'category' => 1],
            ['name' => 'Морковь', 'unit' => 'g', 'price' => 55, 'category' => 1],
            ['name' => 'Лук репчатый', 'unit' => 'g', 'price' => 35, 'category' => 1],
            ['name' => 'Капуста белокочанная', 'unit' => 'g', 'price' => 40, 'category' => 1],
            ['name' => 'Свёкла', 'unit' => 'g', 'price' => 50, 'category' => 1],
            ['name' => 'Апельсины', 'unit' => 'g', 'price' => 130, 'category' => 1],
            // Молочные продукты (смешанные)
            ['name' => 'Молоко 3.2%', 'unit' => 'pcs', 'price' => 75, 'category' => 2],
            ['name' => 'Кефир 1%', 'unit' => 'pcs', 'price' => 68, 'category' => 2],
            ['name' => 'Сыр Российский', 'unit' => 'g', 'price' => 450, 'category' => 2],
            ['name' => 'Сметана 20%', 'unit' => 'pcs', 'price' => 95, 'category' => 2],
            ['name' => 'Творог 9%', 'unit' => 'pcs', 'price' => 120, 'category' => 2],
            ['name' => 'Йогурт питьевой', 'unit' => 'pcs', 'price' => 45, 'category' => 2],
            ['name' => 'Сливочное масло', 'unit' => 'pcs', 'price' => 185, 'category' => 2],
            ['name' => 'Ряженка 4%', 'unit' => 'pcs', 'price' => 72, 'category' => 2],
            ['name' => 'Брынза', 'unit' => 'g', 'price' => 520, 'category' => 2],
            ['name' => 'Сливки 33%', 'unit' => 'pcs', 'price' => 135, 'category' => 2],
            // Сладости и выпечка
            ['name' => 'Торт Наполеон', 'unit' => 'g', 'price' => 380, 'category' => 3],
            ['name' => 'Печенье Орео', 'unit' => 'pcs', 'price' => 95, 'category' => 3],
            ['name' => 'Шоколад Milka', 'unit' => 'pcs', 'price' => 125, 'category' => 3],
            ['name' => 'Круассан', 'unit' => 'pcs', 'price' => 65, 'category' => 3],
            ['name' => 'Пирожное эклер', 'unit' => 'pcs', 'price' => 85, 'category' => 3],
            ['name' => 'Зефир белый', 'unit' => 'g', 'price' => 280, 'category' => 3],
            ['name' => 'Конфеты Raffaello', 'unit' => 'pcs', 'price' => 320, 'category' => 3],
            ['name' => 'Пряники', 'unit' => 'g', 'price' => 195, 'category' => 3],
            ['name' => 'Мармелад', 'unit' => 'g', 'price' => 250, 'category' => 3],
            ['name' => 'Чизкейк', 'unit' => 'g', 'price' => 420, 'category' => 3],
            // Хлеб и бакалея
            ['name' => 'Хлеб белый', 'unit' => 'pcs', 'price' => 45, 'category' => 4],
            ['name' => 'Хлеб бородинский', 'unit' => 'pcs', 'price' => 52, 'category' => 4],
            ['name' => 'Батон нарезной', 'unit' => 'pcs', 'price' => 48, 'category' => 4],
            ['name' => 'Крупа гречневая', 'unit' => 'g', 'price' => 85, 'category' => 4],
            ['name' => 'Рис круглый', 'unit' => 'g', 'price' => 65, 'category' => 4],
            ['name' => 'Макароны спагетти', 'unit' => 'pcs', 'price' => 78, 'category' => 4],
            ['name' => 'Мука пшеничная', 'unit' => 'pcs', 'price' => 95, 'category' => 4],
            ['name' => 'Сахар-песок', 'unit' => 'g', 'price' => 58, 'category' => 4],
            ['name' => 'Соль поваренная', 'unit' => 'pcs', 'price' => 25, 'category' => 4],
            ['name' => 'Булочка с маком', 'unit' => 'pcs', 'price' => 38, 'category' => 4],
            // Напитки
            ['name' => 'Вода минеральная', 'unit' => 'pcs', 'price' => 55, 'category' => 5],
            ['name' => 'Сок апельсиновый', 'unit' => 'pcs', 'price' => 125, 'category' => 5],
            ['name' => 'Кола 0.5л', 'unit' => 'pcs', 'price' => 75, 'category' => 5],
            ['name' => 'Чай зелёный', 'unit' => 'pcs', 'price' => 145, 'category' => 5],
            ['name' => 'Кофе молотый', 'unit' => 'g', 'price' => 850, 'category' => 5],
            ['name' => 'Лимонад', 'unit' => 'pcs', 'price' => 65, 'category' => 5],
            ['name' => 'Энергетик', 'unit' => 'pcs', 'price' => 95, 'category' => 5],
            ['name' => 'Квас', 'unit' => 'pcs', 'price' => 85, 'category' => 5],
            ['name' => 'Морс клюквенный', 'unit' => 'pcs', 'price' => 115, 'category' => 5],
            ['name' => 'Какао-порошок', 'unit' => 'g', 'price' => 420, 'category' => 5],
        ];

        foreach ($products as $index => $data) {
            $slug = Str::slug($data['name']) . '-' . ($index + 1);
            $categoryId = $data['category'] <= count($categoryIds) ? $categoryIds[$data['category'] - 1] : $categoryIds[0];
            $purchasePrice = round($data['price'] * 0.6, 2);

            Product::create([
                'category_id' => $categoryId,
                'name' => $data['name'],
                'new_name' => null,
                'barcode' => $this->generateBarcode(),
                'images' => null,
                'description' => null,
                'unit' => $data['unit'],
                'slug' => $slug,
                'purchase_price' => $purchasePrice,
                'price' => $data['price'],
                'discount_price' => 0,
                'stock' => $data['unit'] === 'pcs' ? rand(10, 100) : rand(1000, 10000),
                'specs' => null,
                'meta' => null,
                'is_active' => true,
            ]);
        }
    }

    private static int $barcodeCounter = 0;

    private function generateBarcode(): string
    {
        self::$barcodeCounter++;
        return '4600' . str_pad((string) (100000000 + self::$barcodeCounter), 9, '0');
    }
}
