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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('event');
            $table->string('description');
            $table->nullableMorphs('subject');
            $table->foreignId('causer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->jsonb('meta')->nullable();
            $table->timestamps();

            $table->index(['event', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
