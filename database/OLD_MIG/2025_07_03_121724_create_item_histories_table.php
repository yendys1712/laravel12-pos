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
        Schema::create('item_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id')->nullable();
            $table->string('item_name');
            $table->enum('action', ['add', 'edit', 'delete']);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

            $table->foreign('id')->references('id')->on('items')->nullOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_histories');
    }
};
