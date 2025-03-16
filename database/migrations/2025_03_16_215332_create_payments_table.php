<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration

{
    /**
     * تشغيل الهجرة
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id(); // المفتاح الأساسي للسند
            $table->unsignedBigInteger('supplier'); // معرف المورد
            $table->unsignedBigInteger('account'); // معرف الحساب
            $table->decimal('amount', 10, 2); // مبلغ السداد
            $table->date('date'); // حقل التاريخ
            $table->text('details'); // حقل التفاصيل (اختياري)
            $table->timestamps();

            // العلاقات مع الجداول الأخرى
            $table->foreign('supplier')->references('id')->on('suppliers')->onDelete('cascade');
            $table->foreign('account')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * إلغاء الهجرة
     */
    public function down()
    {
        Schema::dropIfExists('payment');
    }
}
