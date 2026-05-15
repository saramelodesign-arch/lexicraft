<?php

use App\Support\Editorial\WorkflowStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('concept_translations', function (Blueprint $table): void {
            $table->string('status', 32)
                ->default(WorkflowStatus::DRAFT)
                ->after('language_id')
                ->index();
        });

        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'mysql') {
            DB::statement(
                "UPDATE concept_translations ct
                INNER JOIN concepts c ON c.id = ct.concept_id
                SET ct.status = CASE
                    WHEN c.status = '".WorkflowStatus::PUBLISHED."' THEN '".WorkflowStatus::PUBLISHED."'
                    WHEN c.status = '".WorkflowStatus::REVIEW."' THEN '".WorkflowStatus::REVIEW."'
                    WHEN c.status = '".WorkflowStatus::ARCHIVED."' THEN '".WorkflowStatus::ARCHIVED."'
                    ELSE '".WorkflowStatus::DRAFT."'
                END"
            );
        } elseif ($driver === 'pgsql') {
            DB::statement(
                "UPDATE concept_translations ct
                SET status = CASE
                    WHEN c.status = '".WorkflowStatus::PUBLISHED."' THEN '".WorkflowStatus::PUBLISHED."'
                    WHEN c.status = '".WorkflowStatus::REVIEW."' THEN '".WorkflowStatus::REVIEW."'
                    WHEN c.status = '".WorkflowStatus::ARCHIVED."' THEN '".WorkflowStatus::ARCHIVED."'
                    ELSE '".WorkflowStatus::DRAFT."'
                END
                FROM concepts c
                WHERE c.id = ct.concept_id"
            );
        }
    }

    public function down(): void
    {
        Schema::table('concept_translations', function (Blueprint $table): void {
            $table->dropIndex(['status']);
            $table->dropColumn('status');
        });
    }
};
