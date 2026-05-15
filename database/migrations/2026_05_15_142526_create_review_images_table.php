<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('review_images', function (Blueprint $table) {
            $table->foreignId('review_id')->constrained('reviews')->cascadeOnDelete();
            $table->foreignId('image_id')->constrained('images')->cascadeOnDelete();

            $table->primary(['review_id', 'image_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review_images');
    }
};
