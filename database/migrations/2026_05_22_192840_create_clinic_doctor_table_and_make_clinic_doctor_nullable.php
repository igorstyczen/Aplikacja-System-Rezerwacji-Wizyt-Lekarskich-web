<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('clinic_doctor')) {
            Schema::create('clinic_doctor', function (Blueprint $table) {
                $table->foreignId('clinic_id')->constrained('clinics')->cascadeOnDelete();
                $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();
                $table->timestamps();

                $table->primary(['clinic_id', 'doctor_id']);
            });
        }

        if (Schema::hasColumn('clinics', 'doctor_id')) {
            $driver = Schema::getConnection()->getDriverName();

            if (in_array($driver, ['mysql', 'mariadb'])) {
                DB::statement('ALTER TABLE clinics MODIFY doctor_id BIGINT UNSIGNED NULL');
            }
        }

        if (Schema::hasTable('clinic_doctor') && Schema::hasColumn('clinics', 'doctor_id')) {
            $clinics = DB::table('clinics')
                ->whereNotNull('doctor_id')
                ->get();

            foreach ($clinics as $clinic) {
                $exists = DB::table('clinic_doctor')
                    ->where('clinic_id', $clinic->id)
                    ->where('doctor_id', $clinic->doctor_id)
                    ->exists();

                if (! $exists) {
                    DB::table('clinic_doctor')->insert([
                        'clinic_id' => $clinic->id,
                        'doctor_id' => $clinic->doctor_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('clinic_doctor');

        if (Schema::hasColumn('clinics', 'doctor_id')) {
            $driver = Schema::getConnection()->getDriverName();

            if (in_array($driver, ['mysql', 'mariadb'])) {
                DB::statement('ALTER TABLE clinics MODIFY doctor_id BIGINT UNSIGNED NOT NULL');
            }
        }
    }
};
