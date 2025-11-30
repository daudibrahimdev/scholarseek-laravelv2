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
        Schema::create('mentors', function (Blueprint $table) {
            $table->id();

            // FK ke users table
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->unique();

            $table->text('bio')->nullable();

            // pake JSON karena untuk menyimpan beberapa bidang keahlian
            $table->json('expertise_areas')->nullable();

            $table->string('profile_picture')->nullable();
            $table->string('domicile_city', 100)->nullable();
            $table->text('full_address')->nullable();
            $table->string('phone_number', 15)->nullable();

            // path 
            $table->string('cv_path')->nullable();
            $table->string('motivation_letter_path')->nullable();
        

            // status verifikasi & ketersediaan mentor
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->boolean('is_available')->default(true);
            $table->float('rating', 2, 1)->default(0.0); // Rating 0.0 to 5.0

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mentors');
    }
};
