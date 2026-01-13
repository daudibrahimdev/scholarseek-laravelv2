<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('mentor_reviews', function (Blueprint $table) {
        // Tambahkan kolom yang dibutuhkan
        $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
        $table->foreignId('mentor_id')->after('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('user_package_id')->after('mentor_id')->constrained()->onDelete('cascade');
        $table->integer('rating')->after('user_package_id');
        $table->text('review')->nullable()->after('rating');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('mentor_reviews', function (Blueprint $table) {
        $table->dropForeign(['user_id', 'mentor_id', 'user_package_id']);
        $table->dropColumn(['user_id', 'mentor_id', 'user_package_id', 'rating', 'review']);
    });
}
};
