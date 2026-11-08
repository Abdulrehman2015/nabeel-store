<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('order_items') && !Schema::hasColumn('order_items', 'original_price')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->decimal('original_price', 10, 2)->nullable()->after('unit_price');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('order_items') && Schema::hasColumn('order_items', 'original_price')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->dropColumn('original_price');
            });
        }
    }
};
