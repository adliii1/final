<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('queues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->integer('queue_number'); // Nomor antrian
            $table->date('queue_date'); // Tanggal antrian
            $table->enum('status', ['waiting', 'in_progress', 'completed', 'cancelled'])->default('waiting');
            $table->timestamp('estimated_time')->nullable(); // Estimasi waktu dipanggil
            $table->timestamp('actual_start_time')->nullable(); // Waktu mulai konsultasi
            $table->timestamp('actual_end_time')->nullable(); // Waktu selesai konsultasi
            $table->timestamps();

            $table->index(['queue_date', 'status']);
            $table->index(['booking_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queues');
    }
};
