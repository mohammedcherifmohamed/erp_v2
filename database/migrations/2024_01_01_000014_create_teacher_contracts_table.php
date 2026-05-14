<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('course_id')->nullable()->constrained('courses')->onDelete('set null');
            $table->foreignId('class_id')->nullable()->constrained('classes')->onDelete('set null');
            $table->enum('contract_type', ['percentage', 'per_session', 'per_student', 'fixed_salary']);
            $table->decimal('rate', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('teacher_id');
            $table->index('contract_type');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_contracts');
    }
};