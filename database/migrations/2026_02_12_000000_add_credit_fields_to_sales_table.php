<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->foreignId('counterparty_id')->nullable()->after('shopper_id')->constrained('counterparties')->onDelete('set null');
            $table->boolean('is_on_credit')->default(false)->after('counterparty_id');
            $table->decimal('paid_amount', 10, 2)->default(0)->after('is_on_credit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['counterparty_id']);
            $table->dropColumn(['counterparty_id', 'is_on_credit', 'paid_amount']);
        });
    }
};
