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
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('note')->nullable();
            $table->dateTime('reminder_date');
            $table->boolean('is_completed')->default(false);
            $table->timestamps();

            // Performans için indexler
            $table->index('reminder_date'); // Tarih filtrelemesi için
            $table->index('is_completed'); // Tamamlanma durumu için
            $table->index(['is_completed', 'reminder_date']); // Birleşik sorgu için
        });
    }

    /**
     * Destroy the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};