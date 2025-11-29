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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable(); // Boleh kosong
            $table->string('file_path');
            // Foreign key ke document_categories
            $table->foreignId('document_category_id')->constrained('document_categories')->onDelete('cascade');
            $table->timestamps();
            // Foreign key ke users (uploader)
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
