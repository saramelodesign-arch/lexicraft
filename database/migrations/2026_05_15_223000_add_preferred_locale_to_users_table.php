<?php

use App\Support\Locales;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('preferred_locale', 5)
                ->default(Locales::fallback())
                ->after('email');
            $table->index('preferred_locale');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropIndex(['preferred_locale']);
            $table->dropColumn('preferred_locale');
        });
    }
};
