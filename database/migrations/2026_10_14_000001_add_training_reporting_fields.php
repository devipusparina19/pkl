<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trainings', function (Blueprint $table) {
            if (! Schema::hasColumn('trainings', 'batch_number')) {
                $table->string('batch_number')->nullable()->after('name');
            }
            if (! Schema::hasColumn('trainings', 'funding_source')) {
                $table->string('funding_source')->nullable()->after('batch_number')->index();
            }
            if (! Schema::hasColumn('trainings', 'district')) {
                $table->string('district')->nullable()->after('location')->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('trainings', function (Blueprint $table) {
            foreach (['batch_number', 'funding_source', 'district'] as $column) {
                if (Schema::hasColumn('trainings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};