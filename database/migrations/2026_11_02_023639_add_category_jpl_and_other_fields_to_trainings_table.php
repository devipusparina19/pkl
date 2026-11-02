<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trainings', function (Blueprint $table) {
            if (! Schema::hasColumn('trainings', 'training_category_id')) {
                $table->foreignId('training_category_id')
                    ->nullable()
                    ->constrained('training_categories')
                    ->restrictOnDelete();
            }

            if (! Schema::hasColumn('trainings', 'jpl')) {
                $table->unsignedInteger('jpl')->nullable();
            }

            if (! Schema::hasColumn('trainings', 'batch_number')) {
                $table->string('batch_number')->nullable();
            }

            if (! Schema::hasColumn('trainings', 'funding_source')) {
                $table->string('funding_source')->nullable();
            }

            if (! Schema::hasColumn('trainings', 'district')) {
                $table->string('district')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('trainings', function (Blueprint $table) {
            if (Schema::hasColumn('trainings', 'training_category_id')) {
                $table->dropConstrainedForeignId('training_category_id');
            }

            foreach (['jpl', 'batch_number', 'funding_source', 'district'] as $column) {
                if (Schema::hasColumn('trainings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};