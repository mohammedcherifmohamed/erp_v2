<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE teacher_contracts MODIFY COLUMN contract_type ENUM('percentage', 'per_session', 'per_student', 'monthly') NOT NULL DEFAULT 'percentage'");
        DB::table('teacher_contracts')->where('contract_type', 'fixed_salary')->update(['contract_type' => 'monthly']);
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE teacher_contracts MODIFY COLUMN contract_type ENUM('percentage', 'per_session', 'per_student', 'fixed_salary') NOT NULL DEFAULT 'percentage'");
        DB::table('teacher_contracts')->where('contract_type', 'monthly')->update(['contract_type' => 'fixed_salary']);
    }
};
