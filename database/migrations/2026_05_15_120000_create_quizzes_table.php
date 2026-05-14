<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('locale', 16);
            $table->string('slug');
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('domain_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('is_published')->default(true)->index();
            $table->timestamps();

            $table->unique(['locale', 'slug']);
            $table->index(['locale', 'is_published']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
