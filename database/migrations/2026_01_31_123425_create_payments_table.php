<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('transaction_id')->unique();
            $table->decimal('amount', 10, 2);
            $table->decimal('crypto_amount', 18, 8)->nullable(); // Pour les crypto paiements
            $table->string('currency', 10)->default('EUR');
            $table->enum('payment_method', ['card', 'mobile_money', 'cryptocurrency', 'bank_transfer']);
            $table->enum('status', ['pending', 'completed', 'failed', 'expired'])->default('pending');
            
            // Champs pour Mobile Money
            $table->string('operator')->nullable();
            $table->string('phone_number')->nullable();
            
            // Champs pour Cryptomonnaie
            $table->string('crypto_type')->nullable(); // btc, eth, usdt, etc.
            $table->string('network')->nullable(); // TRC20, ERC20, Bitcoin, etc.
            $table->string('receiving_address')->nullable();
            $table->string('transaction_hash')->nullable();
            
            // Champs pour Virement Bancaire
            $table->string('bank_name')->nullable();
            $table->string('reference')->nullable();
            
            // Champs pour Carte Bancaire
            $table->string('card_last4')->nullable();
            
            // Dates importantes
            $table->dateTime('confirmed_at')->nullable();
            $table->dateTime('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('expires_at')->nullable();
            
            // Métadonnées
            $table->json('metadata')->nullable();
            
            $table->timestamps();
            
            // Index pour les recherches
            $table->index(['user_id', 'status']);
            $table->index(['transaction_id']);
            $table->index(['payment_method', 'status']);
            $table->index(['created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};