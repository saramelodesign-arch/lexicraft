<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('concept_translations', function (Blueprint $table): void {
            $table->index(['language_id', 'status', 'term'], 'ct_lang_status_term_idx');
            $table->index(['language_id', 'status', 'slug'], 'ct_lang_status_slug_idx');
            $table->index(['language_id', 'status', 'concept_id'], 'ct_lang_status_concept_idx');
        });

        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        DB::statement('CREATE EXTENSION IF NOT EXISTS pg_trgm');
        DB::statement(
            "CREATE INDEX IF NOT EXISTS ct_search_tsv_idx
            ON concept_translations
            USING GIN (
                to_tsvector(
                    'simple',
                    coalesce(term, '')
                    || ' ' || coalesce(short_definition, '')
                    || ' ' || coalesce(full_definition, '')
                    || ' ' || coalesce(seo_title, '')
                    || ' ' || coalesce(seo_description, '')
                    || ' ' || coalesce(industry_notes, '')
                )
            )",
        );
        DB::statement('CREATE INDEX IF NOT EXISTS ct_term_trgm_idx ON concept_translations USING GIN (term gin_trgm_ops)');
        DB::statement('CREATE INDEX IF NOT EXISTS ct_slug_trgm_idx ON concept_translations USING GIN (slug gin_trgm_ops)');
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('DROP INDEX IF EXISTS ct_slug_trgm_idx');
            DB::statement('DROP INDEX IF EXISTS ct_term_trgm_idx');
            DB::statement('DROP INDEX IF EXISTS ct_search_tsv_idx');
        }

        Schema::table('concept_translations', function (Blueprint $table): void {
            $table->dropIndex('ct_lang_status_term_idx');
            $table->dropIndex('ct_lang_status_slug_idx');
            $table->dropIndex('ct_lang_status_concept_idx');
        });
    }
};

