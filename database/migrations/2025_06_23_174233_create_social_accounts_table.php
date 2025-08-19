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
        Schema::create('social_accounts', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('provider_id')->constrained('social_providers')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('provider_user_id');
            $table->timestamps();
             $table->unique(['provider_user_id','provider_id' ]);
        });

         Schema::table('users', function (Blueprint $table) {
            //$table->timestamp('email_verified_at')->nullable()->change();
            $table->string('avatar')->nullable();
            $table->string('password')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_accounts');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('avatar');
        });
    }
};
