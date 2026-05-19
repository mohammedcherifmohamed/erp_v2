<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->boolean('show_on_landing')->default(true)->after('is_active');
            $table->string('duration')->nullable()->after('show_on_landing');
            $table->integer('max_students')->nullable()->after('duration');
            $table->string('thumbnail')->nullable()->after('max_students');
            $table->integer('enrolled_count')->default(0)->after('thumbnail');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['show_on_landing', 'duration', 'max_students', 'thumbnail', 'enrolled_count']);
        });
    }
};
