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
        Schema::create('details', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('detail');
            $table->float('total');
            $table->string('tax');
            $table->float('price');
            $table->string('payment_method');
            $table->date('date');
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('branch_id');
            $table->integer('account_id');
            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('branch_id')->references('id')->on('branches');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('details');
    }
};
