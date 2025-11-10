<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('logbook_entries', function (Blueprint $table) {
            // Tambahkan kolom category setelah kolom 'description'
            $table->string('category')->after('description')->default('Perawatan');
        });
    }

    public function down(): void
    {
        Schema::table('logbook_entries', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};