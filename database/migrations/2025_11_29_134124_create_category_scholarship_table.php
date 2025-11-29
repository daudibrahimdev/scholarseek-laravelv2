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
        Schema::create('category_scholarship', function (Blueprint $table) {
            // FK ke tabel scholarships
        $table->foreignId('scholarship_id')->constrained('scholarships')->onDelete('cascade');
        
        // FK ke tabel scholarship_categories
        $table->foreignId('scholarship_category_id')->constrained('scholarship_categories')->onDelete('cascade');
        
        // Kedua kolom FK sebagai primary key komposit
        $table->primary(['scholarship_id', 'scholarship_category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_scholarship');
    }
};
