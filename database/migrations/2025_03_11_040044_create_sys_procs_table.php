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
        Schema::create('sys_procs', function (Blueprint $table) {
            $table->id();
            $table->string('detail');
            $table->string('proc_type');
            $table->string('acc_type');
            $table->integer('account_id');
            $table->float('debt');
            $table->float('credit');
            $table->float('balance');
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sys_procs');
    }
};
