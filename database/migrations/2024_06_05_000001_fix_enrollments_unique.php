<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropUnique('enrollments_student_id_class_id_unique');
            $table->unique(['student_id', 'class_id', 'course_id'], 'enrollments_student_id_class_course_unique');
        });
    }

    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropUnique('enrollments_student_id_class_course_unique');
            $table->unique(['student_id', 'class_id'], 'enrollments_student_id_class_id_unique');
        });
    }
};