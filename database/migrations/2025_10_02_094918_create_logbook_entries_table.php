// File: database/migrations/xxxx_xx_xx_xxxxxx_create_logbook_entries_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logbook_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('priority'); // e.g., 'Normal', 'High Priority'
            $table->string('status')->default('Pending'); // e.g., 'Pending', 'Completed'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logbook_entries');
    }
};