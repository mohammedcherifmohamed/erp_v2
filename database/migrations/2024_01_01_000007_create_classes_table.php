<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_id')->constrained('grades')->onDelete('cascade');
            $table->string('name');
            $table->string('name_ar')->nullable();
            $table->string('section')->nullable();
            $table->integer('capacity')->default(30);
            $table->integer('enrolled_count')->default(0);
            $table->boolean('is_public')->default(false);
            $table->decimal('price', 10, 2)->nullable();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('homeroom_teacher_id')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('grade_id');
            $table->index('is_public');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};