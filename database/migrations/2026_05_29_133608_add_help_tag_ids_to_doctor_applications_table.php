<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doctor_applications', function (Blueprint $table) {
            $table->json('help_tag_ids')->nullable()->after('specialization_ids');
        });
    }

    public function down(): void
    {
        Schema::table('doctor_applications', function (Blueprint $table) {
            $table->dropColumn('help_tag_ids');
        });
    }
};
