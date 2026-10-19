<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_histories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('participant_id')
                ->constrained('participants')
                ->cascadeOnDelete();

            $table->foreignId('training_id')
                ->constrained('trainings')
                ->cascadeOnDelete();

            $table->string('source_file')->nullable();
            $table->string('certificate_number')->nullable();
            $table->string('status')->default('selesai');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(
                ['participant_id', 'training_id'],
                'participant_training_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_histories');
    }
};