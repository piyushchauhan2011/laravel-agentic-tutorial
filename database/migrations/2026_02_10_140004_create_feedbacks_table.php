<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('interview_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('offer_id')->nullable()->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('rating')->default(0);
            $table->text('comments')->nullable();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['interview_id']);
            $table->index(['offer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
