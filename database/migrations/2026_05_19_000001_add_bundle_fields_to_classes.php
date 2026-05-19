<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->decimal('bundle_price', 10, 2)->nullable()->after('reduction_price');
            $table->string('bundle_discount_type')->default('none')->after('bundle_price');
            $table->decimal('bundle_discount_value', 10, 2)->default(0)->after('bundle_discount_type');
            $table->boolean('show_bundle_on_landing')->default(true)->after('bundle_discount_value');
        });
    }

    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropColumn(['bundle_price', 'bundle_discount_type', 'bundle_discount_value', 'show_bundle_on_landing']);
        });
    }
};
