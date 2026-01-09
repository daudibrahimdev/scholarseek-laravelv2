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
            
            // Relasi Utama
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('package_id')->constrained('packages')->onDelete('cascade');
            $table->foreignId('mentor_id')->nullable()->constrained('mentors')->onDelete('set null'); 
            
            // Quota & Durasi
            $table->integer('initial_quota');
            $table->integer('remaining_quota');
            $table->timestamp('purchased_at')->useCurrent();
            $table->timestamp('expires_at')->nullable();
            
            // Kolom Form Tambahan (Target Mentee)
            $table->string('target_country')->nullable();
            $table->string('target_university')->nullable();
            $table->string('target_degree')->nullable(); // S1, S2, S3
            $table->string('target_scholarship')->nullable();
            $table->text('request_note')->nullable();
            
            // Tracking & Penolakan
            $table->timestamp('requested_at')->nullable();
            $table->text('rejection_reason')->nullable();

            // Status Baru
            // pending_assignment: belum pilih mode (manual/auto)
            // pending_approval: menunggu 1 mentor (Manual)
            // open_request: nunggu di Job Board (Otomatis)
            $table->enum('status', [
                'active', 
                'pending_assignment', 
                'pending_approval', 
                'open_request', 
                'rejected', 
                'used_up', 
                'expired'
            ])->default('pending_assignment');
            
            $table->timestamps();

            // Mentee cuma bisa punya satu jenis paket yang sama yang belum kelar
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
