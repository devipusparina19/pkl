<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasColumn('users','username')) Schema::table('users', function (Blueprint $table) { $table->string('username')->nullable()->unique(); });
        if (!Schema::hasColumn('users','role')) Schema::table('users', function (Blueprint $table) { $table->string('role')->default('admin')->index(); });
    }
    public function down(): void { /* Kolom akun sengaja dipertahankan demi keamanan data produksi. */ }
};