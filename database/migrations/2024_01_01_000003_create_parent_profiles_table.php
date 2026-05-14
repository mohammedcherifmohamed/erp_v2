<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parent_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('arabic_name')->nullable();
            $table->string('profession')->nullable();
            $table->string('company')->nullable();
            $table->string('secondary_phone')->nullable();
            $table->enum('relationship', ['father', 'mother', 'guardian', 'other'])->default('father');
            $table->boolean('receive_notifications')->default(true);
            $table->timestamps();

            $table->index('relationship');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parent_profiles');
    }
};