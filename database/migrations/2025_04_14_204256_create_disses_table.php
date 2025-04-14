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
        Schema::create('disses', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->float('slf')->nullable(); // حقل السلفة
            $table->float('absence')->nullable(); // حقل الغياب
            $table->float('discount')->nullable(); // حقل الخصم
            $table->float('bank')->nullable(); // حقل البنك
            $table->unsignedBigInteger('emp_id'); // معرف المورد
            $table->foreign('emp_id')->references('id')->on('emps')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disses');
    }
};
