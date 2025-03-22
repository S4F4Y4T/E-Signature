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
        Schema::create('signers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('email');
            $table->string('mark_coordinate_path')->nullable();
            $table->string('mark_coordinate_filename')->nullable();
            $table->string('signature')->nullable();
            $table->integer('signed')->default(0);
            $table->string('otp')->nullable();
            $table->timestamp('signed_time')->nullable();
            $table->timestamp('last_sent_otp')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signers');
    }
};
