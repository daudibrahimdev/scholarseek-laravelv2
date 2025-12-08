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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
        
        // Siapa yang bayar? (Mentee)
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        
        // Transaksi ini beli apa? (Paket)
        // hubungkan ke user_packages (paket yang dibeli) atau direct package
        $table->foreignId('package_id')->nullable()->constrained('packages')->onDelete('set null');
        
        // Kalau ini transaksi pencairan dana ke Mentor (Withdrawal)
        $table->foreignId('mentor_id')->nullable()->constrained('mentors')->onDelete('cascade');

        // Detail Duit
        $table->decimal('amount', 12, 2); // Jumlah uang
        $table->string('type'); // 'purchase' (pemasukan), 'withdrawal' (penarikan), 'refund'
        $table->enum('status', ['pending', 'paid', 'failed', 'cancelled'])->default('pending');
        
        $table->string('payment_method')->nullable(); // BCA, Gopay, dll.
        $table->string('reference_id')->nullable(); // ID dari Payment Gateway (Midtrans/Xendit misalnya)
        $table->text('description')->nullable();

        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
