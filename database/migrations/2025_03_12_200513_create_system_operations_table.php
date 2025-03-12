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
        Schema::create('system_operations', function (Blueprint $table) {
            $table->id();
            $table->string('operation_type'); // نوع العملية (إضافة، تعديل، حذف)
            $table->text('details')->nullable(); // تفاصيل العملية
            $table->unsignedBigInteger('user_id')->nullable(); // معرف المستخدم (إذا كان هناك تسجيل دخول)
            $table->timestamps(); // تاريخ الإضافة والتعديل
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('system_operations', function (Blueprint $table) {
            //
        });
    }
};
