<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learning_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('trackable_type');
            $table->unsignedBigInteger('trackable_id')->nullable();
            $table->string('locale', 16);
            $table->string('action', 64)->index();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'locale', 'action']);
            $table->index(['trackable_type', 'trackable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_progress');
    }
};
