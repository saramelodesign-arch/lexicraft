<?php

use App\Models\DomainTranslation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('domain_translations', function (Blueprint $table): void {
            $table->string('slug')->nullable()->after('language_id');
        });

        DomainTranslation::query()->with('domain:id,slug')->chunkById(100, function ($translations): void {
            foreach ($translations as $translation) {
                $domainSlug = $translation->domain?->slug;
                if (! is_string($domainSlug) || $domainSlug === '') {
                    continue;
                }
                $translation->forceFill(['slug' => $domainSlug])->saveQuietly();
            }
        });

        Schema::table('domain_translations', function (Blueprint $table): void {
            $table->string('slug')->nullable(false)->change();
            $table->unique(['language_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::table('domain_translations', function (Blueprint $table): void {
            $table->dropUnique(['language_id', 'slug']);
            $table->dropColumn('slug');
        });
    }
};
