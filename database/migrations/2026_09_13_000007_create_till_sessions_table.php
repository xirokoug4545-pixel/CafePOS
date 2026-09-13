<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('till_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opened_by')->constrained('users');
            $table->foreignId('closed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('opening_float', 10, 2);
            $table->decimal('expected_cash', 10, 2)->nullable();
            $table->decimal('counted_cash', 10, 2)->nullable();
            $table->decimal('discrepancy', 10, 2)->nullable();
            $table->text('discrepancy_notes')->nullable();
            $table->timestamp('opened_at');
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('till_sessions');
    }
};