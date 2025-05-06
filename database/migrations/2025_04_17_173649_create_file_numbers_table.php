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
        Schema::create('file_numbers', function (Blueprint $table) {
            $table->id();
            $table->string('file_code'); // كود أو رقم الملف
            $table->integer('adult_limit')->default(0); // الحد الأقصى للكبار
            $table->integer('child_limit')->default(0); // الحد الأقصى للأطفال
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_numbers');
    }
};
