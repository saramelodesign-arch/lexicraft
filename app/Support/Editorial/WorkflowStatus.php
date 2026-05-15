<?php

namespace App\Support\Editorial;

final class WorkflowStatus
{
    public const string DRAFT = 'draft';

    public const string REVIEW = 'review';

    public const string PUBLISHED = 'published';

    public const string ARCHIVED = 'archived';

    /**
     * @return list<string>
     */
    public static function all(): array
    {
        return [
            self::DRAFT,
            self::REVIEW,
            self::PUBLISHED,
            self::ARCHIVED,
        ];
    }

    public static function isValid(string $status): bool
    {
        return in_array($status, self::all(), true);
    }

    public static function isPublic(string $status): bool
    {
        return $status === self::PUBLISHED;
    }
}
