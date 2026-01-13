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
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom baru tanpa mengganggu data yang sudah ada
            $table->string('phone_number')->nullable()->after('email');
            $table->string('university')->nullable()->after('phone_number');
            $table->string('major')->nullable()->after('university');
            $table->text('bio')->nullable()->after('major');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Untuk rollback jika diperlukan
            $table->dropColumn(['phone_number', 'university', 'major', 'bio']);
        });
    }
};
