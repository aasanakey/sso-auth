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
        Schema::create('social_providers', function (Blueprint $table) {
            $table->id();
            $table->string('driver')->unique();
            $table->string('client_id');
            $table->string('client_secret');
            $table->text('redirect_uri');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_providers');
    }
};
