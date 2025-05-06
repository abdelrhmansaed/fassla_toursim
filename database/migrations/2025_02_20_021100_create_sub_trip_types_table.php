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
        Schema::create('sub_trip_types', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->foreignId('trip_type_id')->constrained('trip_types')->onDelete('cascade');
            $table->decimal('adult_price', 8, 2); // سعر البالغ للنوع الفرعي
            $table->decimal('child_price', 8, 2); // سعر الطفل للنوع الفرعي
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_trip_types');
    }
};
