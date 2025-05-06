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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sequence')->nullable();
            $table->foreignId('trip_request_detail_id')->nullable()->constrained('trip_request_details')->onDelete('cascade');
            $table->foreignId('agent_id')->constrained('users')->onDelete('cascade');
            $table->decimal('credit', 10, 2)->nullable();
            $table->decimal('debit', 10, 2)->nullable();
            $table->decimal('total_balance', 10, 2)->nullable();
            $table->decimal('credit_egp', 10, 2)->nullable();
            $table->decimal('debit_egp', 10, 2)->nullable();
            $table->decimal('total_balance_egp', 10, 2)->nullable();
            $table->decimal('credit_usd', 10, 2)->nullable();
            $table->decimal('debit_usd', 10, 2)->nullable();
            $table->decimal('total_balance_usd', 10, 2)->nullable();
            $table->decimal('credit_eur', 10, 2)->nullable();
            $table->decimal('debit_eur', 10, 2)->nullable();
            $table->decimal('total_balance_eur', 10, 2)->nullable();
            $table->text('note')->nullable();
            $table->date('payment_date')->nullable();
            $table->string('image')->nullable();
            $table->enum('currency', ['egp', 'usd', 'eur'])->default('egp');
            $table->string('type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
