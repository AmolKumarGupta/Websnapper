<?php

use App\Models\Payment;
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
        Schema::create('charges', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(User::class)->nullable()
                ->constrained()->onUpdate('cascade')->nullOnDelete();
            $table->foreignIdFor(Payment::class)->nullable()
                ->constrained()->onUpdate('cascade')->nullOnDelete();
            $table->string('payment_intent');
            $table->string('charge');
            $table->string('balance_transaction');
            $table->string('payment_method');
            $table->string('type');
            $table->json('detail');
            $table->integer('amount');
            $table->integer('amount_captured');
            $table->string('currency');
            $table->string('status');
            $table->timestamps();

            $table->unique('payment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('charges');
    }
};
