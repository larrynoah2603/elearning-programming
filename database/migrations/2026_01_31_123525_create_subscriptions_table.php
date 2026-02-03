<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('payment_id')->nullable()->constrained('payments')->onDelete('set null');
            $table->enum('plan', ['monthly', 'quarterly', 'yearly']);
            $table->enum('status', ['active', 'cancelled', 'expired', 'pending'])->default('active');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->dateTime('cancelled_at')->nullable();
            $table->dateTime('reactivated_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->json('metadata')->nullable(); // Pour stocker des infos supplémentaires
            $table->timestamps();
            
            // Index pour les recherches courantes
            $table->index(['user_id', 'status']);
            $table->index(['status', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};