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
        Schema::create('investment_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // 'financial' (Aset finansial) or 'skill' (Investasi Leher ke Atas)
            $table->string('title'); // e.g. "Saham BBCA", "Kelas Cybersecurity Pentest", "CompTIA Security+"
            $table->string('category'); // e.g. Saham, Crypto, Reksa Dana / Kursus, Sertifikasi, Buku
            $table->string('platform')->nullable(); // e.g. Stockbit, Bibit, Binance, Udemy, OffSec, HackTheBox
            $table->decimal('amount', 15, 2);
            $table->date('date');
            $table->text('target_objective')->nullable(); // Target keterangan / goal (e.g. "Target sertifikasi Q4", "Target passive income")
            $table->string('status')->default('active'); // e.g. active, in_progress, completed, planned
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investment_allocations');
    }
};
