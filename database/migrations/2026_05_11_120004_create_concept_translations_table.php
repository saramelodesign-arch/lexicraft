<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('concept_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('concept_id')->constrained()->cascadeOnDelete();
            $table->foreignId('language_id')->constrained('languages')->cascadeOnDelete();
            $table->string('term');
            $table->string('slug');
            $table->text('short_definition')->nullable();
            $table->text('full_definition')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('industry_notes')->nullable();
            $table->timestamps();

            $table->unique(['language_id', 'slug']);
            $table->unique(['concept_id', 'language_id']);
            $table->index('term');
            $table->index('slug');
            $table->index('language_id');
            $table->index('concept_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('concept_translations');
    }
};
