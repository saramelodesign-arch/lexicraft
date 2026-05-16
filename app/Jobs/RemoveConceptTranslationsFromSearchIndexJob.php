<?php

namespace App\Jobs;

use App\Models\ConceptTranslation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Laravel\Scout\EngineManager;

class RemoveConceptTranslationsFromSearchIndexJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public int $timeout = 120;

    public int $uniqueFor = 120;

    /**
     * @param  list<int>  $translationIds
     */
    public function __construct(
        public int $conceptId,
        public array $translationIds,
    ) {}

    public function uniqueId(): string
    {
        return 'search:concept-remove:'.$this->conceptId;
    }

    public function handle(EngineManager $engines): void
    {
        $ids = array_values(array_unique(array_filter(array_map(
            static fn (mixed $id): int => (int) $id,
            $this->translationIds,
        ), static fn (int $id): bool => $id > 0)));

        if ($ids === []) {
            return;
        }

        $models = Collection::make($ids)->map(function (int $id): ConceptTranslation {
            $model = new ConceptTranslation;
            $model->id = $id;
            $model->exists = true;

            return $model;
        });

        $engines->engine()->delete($models);
    }
}

