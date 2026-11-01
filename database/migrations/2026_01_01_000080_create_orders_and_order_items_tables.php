<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique()->index(); // e.g. BS-2026-000101
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            // Customer details
            $table->string('customer_name');
            $table->string('customer_phone')->index();
            $table->string('customer_email')->index();

            // Delivery address & location
            $table->text('delivery_address');
            $table->string('delivery_city');
            $table->integer('floor')->default(0)->comment('0 = Ground floor');
            $table->string('landmark')->nullable();
            $table->text('order_notes')->nullable();

            // Geographic coordinates & zone calculation
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->decimal('distance_km', 8, 2);
            $table->string('delivery_zone', 20)->comment('free or paid');

            // Price breakdowns
            $table->decimal('subtotal', 10, 2);
            $table->decimal('floor_charge', 10, 2)->default(0.00);
            $table->decimal('delivery_charge', 10, 2)->default(0.00);
            $table->decimal('grand_total', 10, 2);

            // Advance & Balance
            $table->decimal('advance_percentage', 5, 2)->default(10.00);
            $table->decimal('advance_amount', 10, 2);
            $table->decimal('balance_amount', 10, 2);

            // Payment proof & verification
            $table->string('payment_method')->default('qr_advance');
            $table->string('payment_reference')->unique()->nullable()->comment('Transaction / Ref ID');
            $table->string('payment_proof_path')->nullable()->comment('Private storage path');
            $table->string('payment_status', 30)->default('pending_verification')->index(); // pending_verification, verified, rejected
            $table->text('payment_rejection_reason')->nullable();
            $table->timestamp('payment_verified_at')->nullable();
            $table->foreignId('payment_verified_by')->nullable()->constrained('users')->nullOnDelete();

            // Order status
            $table->string('order_status', 40)->default('pending_payment_verification')->index();
            // pending_payment_verification, confirmed, processing, out_for_delivery, delivered, cancelled, payment_rejected

            // Internal notes
            $table->text('internal_notes')->nullable();

            // Legal consent & security integrity
            $table->json('accepted_policy_versions')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('idempotency_token', 64)->unique()->nullable();

            $table->timestamps();
        });

        // Order line items
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('product_name');
            $table->string('sku')->nullable();
            $table->string('pack_size')->nullable();
            $table->decimal('unit_price', 10, 2);
            $table->integer('quantity');
            $table->decimal('line_total', 10, 2);
            $table->string('image')->nullable();
            $table->timestamps();
        });

        // Status change history log
        Schema::create('order_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('status_from')->nullable();
            $table->string('status_to');
            $table->text('comment')->nullable();
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_histories');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};

