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
            $table->id(); // Primary key
            $table->foreignId('wallet_id')->constrained()->onDelete('cascade'); // Foreign key to wallets table
            $table->enum('type', ['credit', 'debit']); // Transaction type
            $table->decimal('amount', 15, 2); // Amount with 2 decimal places
            $table->string('description')->nullable(); // Optional description
            $table->timestamps(); // created_at and updated_at
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

