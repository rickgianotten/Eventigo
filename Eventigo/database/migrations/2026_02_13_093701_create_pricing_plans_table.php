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
        Schema::create('pricing_plans', function (Blueprint $table) {
            $table->id();
            $table->enum('name', ['free', 'premium'])->default('free');
            $table->enum('value', ['free', 'premium_monthly','premium_yearly'])->default('free');
            $table->string('cta');
            $table->decimal('price', 8,2)->default(0);
            $table->enum('billing_cycle', ['monthly', 'yearly'])->nullable();
            $table->integer('event_limit')->nullable();
            $table->integer('ticket_limit')->nullable();
            $table->integer('user_limit')->nullable();
            $table->json('features')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricing_plans');
    }
};
