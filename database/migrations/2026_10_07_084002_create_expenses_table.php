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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // 'priority' (Wajib/Tagihan) or 'flexible' (Harian/Opsional)
            $table->string('title');
            $table->string('category');
            $table->decimal('amount', 15, 2);
            $table->date('date');
            $table->text('notes')->nullable(); // catatan detail nominal dan peruntukan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
