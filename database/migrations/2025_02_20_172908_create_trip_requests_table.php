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
        Schema::create('trip_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('booking_number'); // رقم الحجز
            $table->string('receipt_number'); // رقم الإيصال
            $table->decimal('total_price', 10, 2)->default(0);
            $table->decimal('total_price_egp', 10, 2)->default(0);
            $table->decimal('total_price_usd', 10, 2)->default(0);
            $table->decimal('total_price_eur', 10, 2)->default(0);

            $table->string('payment_status')->nullable();
            $table->string('hotel_name'); // اسم الفندق
            $table->timestamps();
        });

    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_requests');
    }
};
