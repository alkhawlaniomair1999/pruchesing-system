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
        Schema::create('casher_procs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->float('total');
            $table->date('date');
            $table->float('bank');
            $table->float('cash');
            $table->float('out');
            $table->float('plus');
            $table->unsignedBigInteger('casher_id');
            $table->foreign('casher_id')->references('id')->on('cashers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('casher_procs');
    }
};
