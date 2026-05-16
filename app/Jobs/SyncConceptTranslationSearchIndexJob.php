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

class SyncConceptTranslationSearchIndexJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public int $timeout = 120;

    public int $uniqueFor = 120;

    public function __construct(public int $translationId) {}

    public function uniqueId(): string
    {
        return 'search:translation:'.$this->translationId;
    }

    public function handle(EngineManager $engines): void
    {
        $translation = ConceptTranslation::query()->find($this->translationId);
        if ($translation === null) {
            $ghost = new ConceptTranslation;
            $ghost->id = $this->translationId;
            $ghost->exists = true;
            $engines->engine()->delete(new Collection([$ghost]));

            return;
        }

        if ($translation->shouldBeSearchable()) {
            $translation->searchable();

            return;
        }

        $translation->unsearchable();
    }
}
