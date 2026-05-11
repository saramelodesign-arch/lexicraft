<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('concept_relations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('concept_id')->constrained('concepts')->cascadeOnDelete();
            $table->foreignId('related_concept_id')->constrained('concepts')->restrictOnDelete();
            $table->string('relation_type', 64)->index();
            $table->timestamps();

            $table->unique(['concept_id', 'related_concept_id', 'relation_type']);
            $table->index('concept_id');
            $table->index('related_concept_id');
        });

        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE concept_relations ADD CONSTRAINT concept_relations_distinct_concepts CHECK (concept_id <> related_concept_id)');
        }
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE concept_relations DROP CONSTRAINT IF EXISTS concept_relations_distinct_concepts');
        }

        Schema::dropIfExists('concept_relations');
    }
};
