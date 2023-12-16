<?php

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
        Schema::create('stripe_customers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->nullable()
                ->constrained()->onUpdate('cascade')->nullOnDelete();
            $table->string('cus_id');
            $table->timestamps();

            $table->index(['user_id', 'cus_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stripe_customers');
    }
};
