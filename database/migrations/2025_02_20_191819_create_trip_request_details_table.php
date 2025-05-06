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
        Schema::create('trip_request_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_request_id')->constrained('trip_requests');
            $table->foreignId('trip_type_id')->constrained('trip_types'); // إضافة العلاقة مع الرحلة
            $table->foreignId('sub_trip_type_id')->nullable()->constrained('sub_trip_types'); // إضافة العلاقة مع الرحلة
            $table->foreignId('provider_id')->constrained('users')->onDelete('cascade');
            $table->integer('total_people'); // إجمالي الأفراد
            $table->integer('adult_count'); // عدد البالغين
            $table->integer('children_count'); // عدد الأطفال
            $table->decimal('adult_price', 10, 2); // سعر الفرد البالغ
            $table->decimal('children_price', 10, 2); // سعر الطفل
            $table->decimal('total_price', 10, 2); // المجموع النهائي
            $table->decimal('total_price_egp', 10, 2); // المجموع النهائي
            $table->decimal('total_price_usd', 10, 2); // المجموع النهائي
            $table->decimal('total_price_eur', 10, 2); // المجموع النهائي
            $table->decimal('converted_total_price_egp', 10, 2)->default(0);
            $table->decimal('commission_value_egp', 10, 2)->default(0);
            $table->decimal('commission_value_usd', 10, 2)->default(0);
            $table->decimal('commission_value_eur', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->nullable(0);
            $table->enum('currency', ['egp', 'usd', 'eur']);
            $table->dateTime('booking_datetime')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('payment_note')->nullable();
            $table->string('image')->nullable();
            $table->enum('status', ['pending', 'waiting_payment',  'confirmed', 'canceled'])->default('pending');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_request_details');
    }
};
