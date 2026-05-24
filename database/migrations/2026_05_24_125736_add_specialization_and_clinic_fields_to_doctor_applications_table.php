<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doctor_applications', function (Blueprint $table) {
            $table->json('specialization_ids')->nullable()->after('bio');

            $table->string('clinic_name')->nullable()->after('specialization_ids');
            $table->string('clinic_city')->nullable()->after('clinic_name');
            $table->string('clinic_address')->nullable()->after('clinic_city');
            $table->text('clinic_details')->nullable()->after('clinic_address');
        });
    }

    public function down(): void
    {
        Schema::table('doctor_applications', function (Blueprint $table) {
            $table->dropColumn([
                'specialization_ids',
                'clinic_name',
                'clinic_city',
                'clinic_address',
                'clinic_details',
            ]);
        });
    }
};
