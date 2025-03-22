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
        Schema::create('signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('signer_id')->constrained()->onDelete('cascade');
            $table->integer('page_no');
            $table->double('coordinate_x');
            $table->double('coordinate_y');
            $table->double('height')->nullable();
            $table->double('width')->nullable();
            $table->string('signature')->nullable();
            $table->integer('signed')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signatures');
    }
};
