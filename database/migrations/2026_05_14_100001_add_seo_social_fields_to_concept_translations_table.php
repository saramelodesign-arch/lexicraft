<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('concept_translations', function (Blueprint $table): void {
            $table->string('seo_canonical_url', 2048)->nullable()->after('seo_description');
            $table->string('og_title', 512)->nullable()->after('seo_canonical_url');
            $table->text('og_description')->nullable()->after('og_title');
        });
    }

    public function down(): void
    {
        Schema::table('concept_translations', function (Blueprint $table): void {
            $table->dropColumn(['seo_canonical_url', 'og_title', 'og_description']);
        });
    }
};
