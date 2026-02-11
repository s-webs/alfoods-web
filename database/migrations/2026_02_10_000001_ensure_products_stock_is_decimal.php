<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * Убеждаемся, что stock — decimal(10,2), а не integer (чтобы хранить 1.25 и т.д.).
     */
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE products MODIFY stock DECIMAL(10,2) NOT NULL DEFAULT 0.00');
        } else {
            Schema::table('products', function (Blueprint $table) {
                $table->decimal('stock', 10, 2)->default(0)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Не меняем обратно, чтобы не сломать данные
    }
};
