<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctors_help_tags', function (Blueprint $table) {
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained('help_tags')->cascadeOnDelete();

            $table->primary(['doctor_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctors_help_tags');
    }
};
