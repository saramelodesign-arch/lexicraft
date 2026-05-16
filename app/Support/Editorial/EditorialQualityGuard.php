<?php

namespace App\Support\Editorial;

use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

final class EditorialQualityGuard
{
    private const array BLOCKED_SNIPPETS = [
        'lorem ipsum',
        'todo',
        'tbd',
        'coming soon',
        'placeholder',
        'test definition',
    ];

    /**
     * @param  list<array{id?: int|null, example?: string|null, context?: string|null, sort_order?: int|null}>  $examples
     */
    public static function assertTranslationQuality(
        string $term,
        ?string $shortDefinition,
        ?string $fullDefinition,
        array $examples,
        string $translationStatus,
    ): void {
        $errors = [];

        $cleanTerm = trim($term);
        if ($cleanTerm === '' || mb_strlen($cleanTerm, 'UTF-8') < 2) {
            $errors['term'] = __('admin.msg_term_min_chars');
        }

        self::assertNoWeakContent('term', $cleanTerm, $errors);
        self::assertNoWeakContent('short_definition', (string) $shortDefinition, $errors);
        self::assertNoWeakContent('full_definition', (string) $fullDefinition, $errors);

        $isPublishedLike = in_array($translationStatus, [WorkflowStatus::REVIEW, WorkflowStatus::PUBLISHED], true);
        if ($isPublishedLike) {
            if (trim((string) $shortDefinition) === '') {
                $errors['short_definition'] = __('admin.msg_short_definition_required');
            }

            if (trim((string) $fullDefinition) === '') {
                $errors['full_definition'] = __('admin.msg_full_definition_required');
            }
        }

        $seenExamples = [];
        foreach ($examples as $index => $row) {
            $example = trim((string) ($row['example'] ?? ''));
            if ($example === '') {
                continue;
            }

            self::assertNoWeakContent("examples.{$index}.example", $example, $errors);
            $key = Str::of($example)->lower()->squish()->toString();
            if (isset($seenExamples[$key])) {
                $errors["examples.{$index}.example"] = __('admin.msg_duplicate_examples');
                continue;
            }

            $seenExamples[$key] = true;
        }

        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }
    }

    /**
     * @param  array<string, string>  $errors
     */
    private static function assertNoWeakContent(string $field, string $value, array &$errors): void
    {
        $normalized = Str::of($value)->lower()->squish()->toString();
        if ($normalized === '') {
            return;
        }

        foreach (self::BLOCKED_SNIPPETS as $blocked) {
            if (str_contains($normalized, $blocked)) {
                $errors[$field] = __('admin.msg_placeholder_disallowed');
                return;
            }
        }
    }
}
