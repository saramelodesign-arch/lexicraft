<?php

namespace Database\Seeders;

use App\Models\Concept;
use App\Models\Language;
use App\Support\FootwearConstructionMediaAuthority;
use App\Support\TechnicalFootwearDiagramSvg;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FootwearConstructionConceptMediaSeeder extends Seeder
{
    public function run(): void
    {
        $englishId = Language::query()->where('code', 'en')->value('id');
        if ($englishId === null) {
            return;
        }

        foreach (FootwearConstructionMediaAuthority::PRIORITY_CONCEPT_KEYS as $conceptKey) {
            $concept = Concept::query()
                ->whereHas('translations', function ($query) use ($englishId, $conceptKey): void {
                    $query->where('language_id', $englishId)
                        ->where('slug', $conceptKey);
                })
                ->first();

            if ($concept === null) {
                continue;
            }

            foreach (FootwearConstructionMediaAuthority::mediaSlotsFor($conceptKey) as $slot) {
                $this->seedSlot($concept, $conceptKey, $slot);
            }
        }
    }

    /**
     * @param  array<string, mixed>  $slot
     */
    private function seedSlot(Concept $concept, string $conceptKey, array $slot): void
    {
        $slotId = (string) ($slot['slot_id'] ?? '');
        if ($slotId === '') {
            return;
        }

        $already = $concept->media()
            ->where('custom_properties->authority_slot', $slotId)
            ->exists();

        if ($already) {
            return;
        }

        $variant = (string) ($slot['variant'] ?? TechnicalFootwearDiagramSvg::VARIANT_WORKFLOW);
        $svg = TechnicalFootwearDiagramSvg::render($conceptKey, $variant);
        $tmp = tempnam(sys_get_temp_dir(), 'lex-svg-');
        if ($tmp === false) {
            return;
        }

        $path = $tmp.'.svg';
        File::put($path, $svg);

        try {
            $collection = match ($slot['collection'] ?? 'gallery') {
                'featured' => Concept::COLLECTION_FEATURED,
                default => Concept::COLLECTION_GALLERY,
            };

            $locales = is_array($slot['locales'] ?? null) ? $slot['locales'] : [];

            $concept->addMedia($path)
                ->usingFileName("{$conceptKey}-{$variant}.svg")
                ->withCustomProperties([
                    'authority_slot' => $slotId,
                    'kind' => $slot['kind'] ?? 'diagram',
                    'semantic_role' => $slot['semantic_role'] ?? 'construction',
                    'source_label' => 'LexiCraft technical schematic',
                    'locales' => $locales,
                ])
                ->toMediaCollection($collection);
        } finally {
            @unlink($path);
            @unlink($tmp);
        }
    }
}
