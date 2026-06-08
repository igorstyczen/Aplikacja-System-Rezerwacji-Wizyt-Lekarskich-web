<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('specializations')) {
            Schema::create('specializations', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->timestamps();
            });
        }

        if (
            Schema::hasTable('doctor_specializations')
            && ! Schema::hasColumn('doctor_specializations', 'specialization_id')
        ) {
            Schema::table('doctor_specializations', function (Blueprint $table) {
                $table->foreignId('specialization_id')
                    ->nullable()
                    ->after('doctor_id')
                    ->constrained('specializations')
                    ->nullOnDelete();
            });
        }

        if (Schema::hasTable('doctor_specializations') && Schema::hasTable('specializations')) {
            $existingSpecializations = DB::table('doctor_specializations')
                ->select('specialization_name')
                ->whereNotNull('specialization_name')
                ->where('specialization_name', '!=', '')
                ->distinct()
                ->pluck('specialization_name');

            foreach ($existingSpecializations as $specializationName) {
                $specialization = DB::table('specializations')
                    ->where('name', $specializationName)
                    ->first();

                if ($specialization) {
                    $specializationId = $specialization->id;
                } else {
                    $specializationId = DB::table('specializations')->insertGetId([
                        'name' => $specializationName,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                if (Schema::hasColumn('doctor_specializations', 'specialization_id')) {
                    DB::table('doctor_specializations')
                        ->where('specialization_name', $specializationName)
                        ->whereNull('specialization_id')
                        ->update([
                            'specialization_id' => $specializationId,
                            'updated_at' => now(),
                        ]);
                }
            }
        }
    }

    public function down(): void
    {
        if (
            Schema::hasTable('doctor_specializations')
            && Schema::hasColumn('doctor_specializations', 'specialization_id')
        ) {
            Schema::table('doctor_specializations', function (Blueprint $table) {
                $table->dropForeign(['specialization_id']);
                $table->dropColumn('specialization_id');
            });
        }

        Schema::dropIfExists('specializations');
    }
};
