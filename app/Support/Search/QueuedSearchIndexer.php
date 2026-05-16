<?php

namespace App\Support\Search;

use App\Jobs\RemoveConceptTranslationsFromSearchIndexJob;
use App\Jobs\SyncConceptSearchIndexJob;
use App\Jobs\SyncConceptTranslationSearchIndexJob;

final class QueuedSearchIndexer
{
    public static function queueConcept(int $conceptId): void
    {
        if ($conceptId <= 0) {
            return;
        }

        SyncConceptSearchIndexJob::dispatch($conceptId)->afterCommit();
    }

    public static function queueTranslation(int $translationId): void
    {
        if ($translationId <= 0) {
            return;
        }

        SyncConceptTranslationSearchIndexJob::dispatch($translationId)->afterCommit();
    }

    /**
     * @param  list<int>  $translationIds
     */
    public static function queueConceptRemoval(int $conceptId, array $translationIds): void
    {
        if ($conceptId <= 0 || $translationIds === []) {
            return;
        }

        $ids = array_values(array_unique(array_filter(array_map(
            static fn (mixed $id): int => (int) $id,
            $translationIds,
        ), static fn (int $id): bool => $id > 0)));

        if ($ids === []) {
            return;
        }

        RemoveConceptTranslationsFromSearchIndexJob::dispatch($conceptId, $ids)->afterCommit();
    }
}
