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
        Schema::create('invoice_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id'); // معرف المورد
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->string('product_code')->nullable(); // كود المنتج
            $table->string('product_name'); // اسم المنتج
            $table->integer('quantity'); // الكمية
            $table->float('price'); // سعر الوحدة
            $table->float('discount'); // الخصم
            $table->string('tax'); // المجموع
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_details');
    }
};
