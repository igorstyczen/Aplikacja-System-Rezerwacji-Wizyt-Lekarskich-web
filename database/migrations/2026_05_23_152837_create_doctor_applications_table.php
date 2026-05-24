<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone')->nullable();
            $table->text('bio')->nullable();

            $table->boolean('is_for_adults')->default(true);
            $table->boolean('is_for_children')->default(false);

            $table->string('status')->default('pending');
            $table->text('admin_note')->nullable();
            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_applications');
    }
};
