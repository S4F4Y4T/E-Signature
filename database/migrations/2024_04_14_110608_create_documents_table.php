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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('sender_name');
            $table->string('sender_email');
            $table->string('sender_ip')->nullable();
            $table->string('original_filename');
            $table->string('signed_filename')->nullable();
            $table->string('original_path');
            $table->string('signed_path')->nullable();
            $table->string('short_url')->nullable();
            $table->string('status')->default('On Going');
            $table->integer('completed_signed')->default(0);
            $table->dateTime('last_signed_time')->nullable();
            $table->integer('total_signer')->default(0);
            $table->integer('total_signed')->default(0);
            $table->integer('signed')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
