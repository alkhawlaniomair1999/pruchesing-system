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
    Schema::create('supply_details', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('supplier_id'); // معرف المورد
        $table->decimal('amount', 15, 2); // قيمة التوريد
        $table->string('payment_type'); // نوع الدفع (نقداً أو آجلاً)
        $table->text('details')->nullable(); // تفاصيل إضافية
        $table->string('account_name')->nullable(); // اسم الحساب (إذا كان نقداً)
        $table->date('date');
        $table->timestamps(); // تاريخ الإضافة والتعديل
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supply_details', function (Blueprint $table) {
            //
        });
    }
};
