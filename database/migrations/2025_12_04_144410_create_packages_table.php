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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->unique(); // Nama paket (e.g., 'Ultimate Class')
            $table->text('description')->nullable();
            
            // Harga paket yang dibayar mentee
            $table->decimal('price', 10, 2); 
            
            // QUOTA & TIPE
            // Total sesi/jam yang didapat dari paket ini
            $table->integer('quota_sessions')->default(0); 
            
            // Tipe: private, group, atau hybrid (penting untuk validasi booking)
            $table->enum('type', ['private', 'group', 'hybrid']); 
            
            // Masa aktif paket (dalam hari). Nullable jika paket tidak memiliki batas waktu
            $table->integer('duration_days')->nullable(); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
