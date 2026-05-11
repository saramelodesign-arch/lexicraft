<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('examples', function (Blueprint $table) {
            $table->id();
            $table->foreignId('concept_translation_id')->constrained('concept_translations')->cascadeOnDelete();
            $table->text('example');
            $table->string('context', 64)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index('context');
            $table->index('concept_translation_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('examples');
    }
};
