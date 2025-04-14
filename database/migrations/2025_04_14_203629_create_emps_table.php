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
        Schema::create('emps', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name_emp')->nullable();
            $table->string('branch')->nullable();
            $table->string('country')->nullable();
            $table->string('salary')->nullable();
            $table->date('date_hirring')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emps');
    }
};
