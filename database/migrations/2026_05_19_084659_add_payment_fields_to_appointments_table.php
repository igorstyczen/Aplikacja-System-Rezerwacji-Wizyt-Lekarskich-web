<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('payment_status')->default('unpaid')->after('status');
            $table->string('payment_method')->nullable()->after('payment_status');
            $table->decimal('payment_amount', 8, 2)->nullable()->after('payment_method');
            $table->timestamp('paid_at')->nullable()->after('payment_amount');
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn([
                'payment_status',
                'payment_method',
                'payment_amount',
                'paid_at',
            ]);
        });
    }
};
