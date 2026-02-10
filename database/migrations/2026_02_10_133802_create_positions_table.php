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
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('department')->nullable();
            $table->string('employment_type')->default('full_time');
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('draft');
            $table->timestampTz('published_at')->nullable();
            $table->timestampTz('closing_at')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'status']);
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
