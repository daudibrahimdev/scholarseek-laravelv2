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
        Schema::create('learning_sessions', function (Blueprint $table) {
            $table->id();
            
            // FK ke tabel mentors (PENTING: pastikan tabel mentors sudah ada)
            $table->foreignId('mentor_id')->constrained('mentors')->onDelete('cascade');
            
            $table->string('title');
            $table->enum('type', ['group', '1on1']);
            $table->dateTime('start_time'); // Gunakan dateTime, bukan date
            $table->dateTime('end_time');   // Tambahan: Selesainya jam berapa
            $table->integer('max_participants')->nullable(); // Nullable untuk 1on1 (default 1)
            $table->string('url_meeting')->nullable(); // Link Zoom/Gmeet
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0); // Tambahan: Harga sesi (0 = gratis)
            
            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'cancelled'])->default('scheduled');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_sessions');
    }
};
