<?php

use App\Support\Editorial\TerminologyStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('concept_translations', function (Blueprint $table): void {
            $table->string('terminology_status', 32)
                ->default(TerminologyStatus::DRAFT)
                ->after('status')
                ->index();
            $table->timestamp('validated_at')->nullable()->after('terminology_status');
            $table->foreignId('validated_by')
                ->nullable()
                ->after('validated_at')
                ->constrained('users')
                ->nullOnDelete();
            $table->text('editorial_notes')->nullable()->after('industry_notes');
            $table->text('source_reference_text')->nullable()->after('editorial_notes');
        });
    }

    public function down(): void
    {
        Schema::table('concept_translations', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('validated_by');
            $table->dropIndex(['terminology_status']);
            $table->dropColumn([
                'terminology_status',
                'validated_at',
                'editorial_notes',
                'source_reference_text',
            ]);
        });
    }
};

