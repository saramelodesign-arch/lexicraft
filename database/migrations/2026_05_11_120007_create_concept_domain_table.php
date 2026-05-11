<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('concept_domain', function (Blueprint $table) {
            $table->foreignId('concept_id')->constrained()->cascadeOnDelete();
            $table->foreignId('domain_id')->constrained()->cascadeOnDelete();

            $table->primary(['concept_id', 'domain_id']);
            $table->index('concept_id');
            $table->index('domain_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('concept_domain');
    }
};
