<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->unsignedInteger('level')->default(0);
            $table->timestamps();

            $table->index(['parent_id', 'level']);
        });

        // Add department_id to positions table
        Schema::table('positions', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->after('company_id')->constrained('departments')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('department_id');
        });

        Schema::dropIfExists('departments');
    }
};
