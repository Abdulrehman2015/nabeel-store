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
        // 1. Outbox / Sync Queue table for offline-first replication
        if (!Schema::hasTable('sync_queues')) {
            Schema::create('sync_queues', function (Blueprint $table) {
                $table->id();
                $table->string('sync_uuid', 64)->unique()->index();
                $table->string('record_type', 50)->index(); // 'order', 'product', 'product_expense', 'customer'
                $table->unsignedBigInteger('record_id')->nullable()->index();
                $table->string('action', 20)->default('created'); // 'created', 'updated', 'deleted'
                $table->longText('payload'); // JSON serialized data
                $table->string('status', 20)->default('pending')->index(); // 'pending', 'syncing', 'synced', 'failed'
                $table->unsignedSmallInteger('attempts')->default(0);
                $table->text('error_message')->nullable();
                $table->timestamp('synced_at')->nullable();
                $table->timestamps();
            });
        }

        // 2. Add sync_uuid and sync_status to orders table if not present
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (!Schema::hasColumn('orders', 'sync_uuid')) {
                    $table->string('sync_uuid', 64)->nullable()->unique()->after('idempotency_token');
                }
                if (!Schema::hasColumn('orders', 'sync_status')) {
                    $table->string('sync_status', 20)->default('synced')->index()->after('order_status');
                }
            });
        }

        // 3. Add sync_uuid and sync_status to products table if not present
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (!Schema::hasColumn('products', 'sync_uuid')) {
                    $table->string('sync_uuid', 64)->nullable()->unique()->after('sku');
                }
                if (!Schema::hasColumn('products', 'sync_status')) {
                    $table->string('sync_status', 20)->default('synced')->index()->after('is_active');
                }
            });
        }

        // 4. Add sync_uuid to product_expenses if exists
        if (Schema::hasTable('product_expenses')) {
            Schema::table('product_expenses', function (Blueprint $table) {
                if (!Schema::hasColumn('product_expenses', 'sync_uuid')) {
                    $table->string('sync_uuid', 64)->nullable()->unique()->after('id');
                }
                if (!Schema::hasColumn('product_expenses', 'sync_status')) {
                    $table->string('sync_status', 20)->default('synced')->index()->after('expense_date');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sync_queues');

        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (Schema::hasColumn('orders', 'sync_uuid')) {
                    $table->dropColumn('sync_uuid');
                }
                if (Schema::hasColumn('orders', 'sync_status')) {
                    $table->dropColumn('sync_status');
                }
            });
        }

        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (Schema::hasColumn('products', 'sync_uuid')) {
                    $table->dropColumn('sync_uuid');
                }
                if (Schema::hasColumn('products', 'sync_status')) {
                    $table->dropColumn('sync_status');
                }
            });
        }

        if (Schema::hasTable('product_expenses')) {
            Schema::table('product_expenses', function (Blueprint $table) {
                if (Schema::hasColumn('product_expenses', 'sync_uuid')) {
                    $table->dropColumn('sync_uuid');
                }
                if (Schema::hasColumn('product_expenses', 'sync_status')) {
                    $table->dropColumn('sync_status');
                }
            });
        }
    }
};
