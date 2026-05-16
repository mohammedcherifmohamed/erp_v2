<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('reduction_amount', 10, 2)->default(0)->after('total_amount');
            $table->text('reduction_reason')->nullable()->after('reduction_amount');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['reduction_amount', 'reduction_reason']);
        });
    }
};
