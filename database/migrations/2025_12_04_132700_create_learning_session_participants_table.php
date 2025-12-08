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
        Schema::create('learning_session_participants', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke Sesi
            $table->foreignId('learning_session_id')->constrained('learning_sessions')->onDelete('cascade');
            
            // Relasi ke User (Mentee yang daftar)
            $table->foreignId('mentee_id')->constrained('users')->onDelete('cascade');
            
            // Relasi ke Transaksi (Nanti di-connect setelah tabel transactions dibuat)
            $table->unsignedBigInteger('transaction_id')->nullable(); 
            
            // Status Pendaftaran
            $table->enum('status', ['pending', 'registered', 'cancelled', 'attended'])->default('pending');
            
            $table->text('notes')->nullable(); // Catatan/Pesan dari mentee ke mentor
            
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_session_participants');
    }
};
