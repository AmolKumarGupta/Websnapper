<?php

use App\Models\Plan;
use App\Models\User;
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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->nullable()
                ->constrained()->onUpdate('cascade')->nullOnDelete();
            $table->foreignIdFor(Plan::class)->nullable()
                ->constrained()->onUpdate('cascade')->nullOnDelete();
            $table->string('customer');
            $table->string('payment_intent');
            $table->integer('amount')->comment('calculated amount in lowest unit');
            $table->string('currency');
            $table->string('status')->default('incomplete');
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['payment_intent', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
