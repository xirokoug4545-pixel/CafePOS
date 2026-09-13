<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (! Schema::hasColumn('order_items', 'status')) {
                $table->enum('status', ['active', 'voided'])->default('active')->after('selected_options');
            }
            if (! Schema::hasColumn('order_items', 'voided_by')) {
                $table->foreignId('voided_by')->nullable()->after('status');
            }
            if (! Schema::hasColumn('order_items', 'void_reason')) {
                $table->text('void_reason')->nullable()->after('voided_by');
            }
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('voided_by');
            $table->dropColumn(['status', 'void_reason']);
        });
    }
};