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
        Schema::create('trip_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // ربط بمزود الخدمة
            $table->string('type'); // اسم النوع الرئيسي (مثل بحري)
            $table->decimal('adult_price', 8, 2); // سعر البالغ للنوع الرئيسي
            $table->decimal('child_price', 8, 2); // سعر الطفل للنوع الرئيسي
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_types');
    }
};
