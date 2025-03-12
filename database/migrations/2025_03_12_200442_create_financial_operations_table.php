<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('financial_operations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('related_id')->nullable(); // معرف المرتبط (الحساب أو المورد)
            $table->string('related_type'); // نوع المرتبط (حساب أو مورد)
            $table->string('operation_type'); // نوع العملية (إيداع، سحب، توريد، إلخ)
            $table->decimal('debit', 15, 2)->default(0); // مدين
            $table->decimal('credit', 15, 2)->default(0); // دائن
            $table->decimal('balance', 15, 2); // الرصيد بعد العملية
            $table->text('details')->nullable(); // تفاصيل إضافية
            $table->unsignedBigInteger('user_id')->nullable(); // معرف المستخدم
            $table->timestamps(); // تاريخ الإضافة والتعديل
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('financial_operations', function (Blueprint $table) {
            //
        });
    }
};
