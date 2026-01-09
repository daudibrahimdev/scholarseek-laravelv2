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
        Schema::create('user_packages', function (Blueprint $table) {
            $table->id();
            
            // 1. Foreign Key ke Mentee (User)
            // Memastikan paket ini dimiliki oleh Mentee yang terdaftar
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            
            // 2. Foreign Key ke Paket (Package)
            // Menunjukkan paket mana yang dibeli (e.g., Ultimate Class)
            $table->foreignId('package_id')
                  ->constrained('packages')
                  ->onDelete('cascade');
            
            // 3. KUNCI SKEMA A: Mentor yang di-assign
            // Jika paket sudah dibeli tapi belum pilih mentor, ini akan NULL.
            // Jika Mentor dihapus, kolom ini di-SET NULL (tidak menghapus paket mentee)
            $table->foreignId('mentor_id')
                  ->nullable()
                  ->constrained('mentors')
                  ->onDelete('set null'); 
            
            // 4. Quota Management
            $table->integer('initial_quota'); // Total kuota awal yang didapat (misal: 30)
            $table->integer('remaining_quota'); // Sisa kuota (ini yang akan dipotong saat booking)
            
            // 5. Status & Durasi
            $table->timestamp('purchased_at');
            $table->timestamp('expires_at')->nullable(); // Kapan paket ini kadaluarsa
            
            // Status:
            // - pending_assignment: Sudah bayar, tapi belum pilih Mentor.
            // - active: Mentor sudah dipilih, kuota bisa digunakan.
            // - used_up: Kuota sudah habis (remaining_quota = 0).
            // - expired: Sudah lewat expires_at.
            $table->enum('status', ['active', 'pending_assignment', 'used_up', 'expired'])
                  ->default('pending_assignment');
            
            $table->timestamps();

            // Index dan Unique Constraints
            // Mentee hanya bisa memiliki satu paket yang sama (package_id) yang aktif/pending pada satu waktu.
            $table->unique(['user_id', 'package_id']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_packages');
    }
};
