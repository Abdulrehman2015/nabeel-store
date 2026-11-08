<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('product_expenses')) {
            Schema::create('product_expenses', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('product_id')->nullable()->index();
                $table->string('product_name');
                $table->string('product_sku')->nullable()->index();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->string('staff_name')->nullable();
                $table->string('category', 50)->default('staff_consumption'); // staff_consumption, store_use, damaged_expired, other
                $table->integer('quantity')->default(1);
                $table->decimal('unit_cost', 10, 2)->default(0.00);
                $table->decimal('total_cost', 10, 2)->default(0.00);
                $table->string('reason')->nullable();
                $table->date('expense_date')->index();
                $table->timestamps();

                $table->index(['expense_date', 'category']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('product_expenses');
    }
};

