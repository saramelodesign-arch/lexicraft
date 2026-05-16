<?php

namespace App\Jobs;

use App\Models\ConceptTranslation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncConceptSearchIndexJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public int $timeout = 120;

    public int $uniqueFor = 120;

    public function __construct(public int $conceptId) {}

    public function uniqueId(): string
    {
        return 'search:concept:'.$this->conceptId;
    }

    public function handle(): void
    {
        ConceptTranslation::query()
            ->where('concept_id', $this->conceptId)
            ->chunkById(100, function ($chunk): void {
                foreach ($chunk as $translation) {
                    if ($translation->shouldBeSearchable()) {
                        $translation->searchable();

                        continue;
                    }

                    $translation->unsearchable();
                }
            });
    }
}
