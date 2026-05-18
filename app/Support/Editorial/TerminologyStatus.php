<?php

namespace App\Support\Editorial;

final class TerminologyStatus
{
    public const string DRAFT = 'draft';

    public const string REVIEWED = 'reviewed';

    public const string VALIDATED = 'validated';

    public const string REGIONAL = 'regional';

    public const string DEPRECATED = 'deprecated';

    /**
     * @return list<string>
     */
    public static function all(): array
    {
        return [
            self::DRAFT,
            self::REVIEWED,
            self::VALIDATED,
            self::REGIONAL,
            self::DEPRECATED,
        ];
    }

    public static function isValid(string $status): bool
    {
        return in_array($status, self::all(), true);
    }

    public static function isWorkflowCoherent(string $terminologyStatus, string $workflowStatus): bool
    {
        if (! self::isValid($terminologyStatus) || ! WorkflowStatus::isValid($workflowStatus)) {
            return false;
        }

        if ($terminologyStatus === self::VALIDATED) {
            return in_array($workflowStatus, [WorkflowStatus::REVIEW, WorkflowStatus::PUBLISHED], true);
        }

        if ($terminologyStatus === self::REVIEWED) {
            return in_array($workflowStatus, [WorkflowStatus::REVIEW, WorkflowStatus::PUBLISHED], true);
        }

        if ($terminologyStatus === self::DEPRECATED) {
            return $workflowStatus !== WorkflowStatus::DRAFT;
        }

        return true;
    }

    public static function requiresEditorialContext(string $status): bool
    {
        return in_array($status, [self::REGIONAL, self::DEPRECATED], true);
    }
}

