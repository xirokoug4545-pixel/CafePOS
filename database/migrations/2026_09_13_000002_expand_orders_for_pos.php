<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id');
            }
            if (! Schema::hasColumn('orders', 'order_mode')) {
                $table->enum('order_mode', ['counter', 'table'])->default('counter')->after('user_id');
            }
            if (! Schema::hasColumn('orders', 'table_name')) {
                $table->string('table_name')->nullable()->after('order_mode');
            }
            if (! Schema::hasColumn('orders', 'subtotal')) {
                $table->decimal('subtotal', 10, 2)->default(0)->after('total_amount');
            }
            if (! Schema::hasColumn('orders', 'discount_amount')) {
                $table->decimal('discount_amount', 10, 2)->default(0)->after('subtotal');
            }
            if (! Schema::hasColumn('orders', 'tip_amount')) {
                $table->decimal('tip_amount', 10, 2)->default(0)->after('discount_amount');
            }
            if (! Schema::hasColumn('orders', 'payment_status')) {
                $table->enum('payment_status', ['unpaid', 'partial', 'paid', 'refunded'])->default('unpaid')->after('tip_amount');
            }
            if (! Schema::hasColumn('orders', 'held_at')) {
                $table->timestamp('held_at')->nullable()->after('payment_status');
            }
            if (! Schema::hasColumn('orders', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('held_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn(['order_mode', 'table_name', 'subtotal', 'discount_amount', 'tip_amount', 'payment_status', 'held_at', 'completed_at']);
        });
    }
};