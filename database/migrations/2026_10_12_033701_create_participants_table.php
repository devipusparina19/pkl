<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 30)->unique();
            $table->string('name');
            $table->string('institution')->nullable();
            $table->string('unit')->nullable();
            $table->string('district')->nullable();
            $table->string('province')->default('Kalimantan Selatan');
            $table->string('position')->nullable();
            $table->string('profession')->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 30)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};