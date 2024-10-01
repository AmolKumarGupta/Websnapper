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
        Schema::create('folders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->char('hash', 6)->index();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('videos', function (Blueprint $table) {
            $table->unsignedBigInteger('folder_id')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('folders');

        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn('folder_id');
        });
    }
};
