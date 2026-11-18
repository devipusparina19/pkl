<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trainings', function (Blueprint $table) {
            // Durasi hasil penyesuaian admin. NULL = pakai durasi dari Excel.
            $table->unsignedSmallInteger('duration_manual')->nullable()->after('duration_days');
            $table->string('duration_note', 255)->nullable()->after('duration_manual');
            $table->timestamp('duration_adjusted_at')->nullable()->after('duration_note');
            $table->unsignedBigInteger('duration_adjusted_by')->nullable()->after('duration_adjusted_at');
        });
    }

    public function down(): void
    {
        Schema::table('trainings', function (Blueprint $table) {
            $table->dropColumn([
                'duration_manual',
                'duration_note',
                'duration_adjusted_at',
                'duration_adjusted_by',
            ]);
        });
    }
};