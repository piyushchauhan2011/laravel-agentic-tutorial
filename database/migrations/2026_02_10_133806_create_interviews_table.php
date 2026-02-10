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
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->timestampTz('scheduled_for');
            $table->string('interviewer_name');
            $table->string('interviewer_email')->nullable();
            $table->string('mode')->default('video');
            $table->string('status')->default('scheduled');
            $table->text('feedback')->nullable();
            $table->timestamps();

            $table->index(['scheduled_for', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};
