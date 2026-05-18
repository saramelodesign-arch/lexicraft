<?php

namespace Tests\Feature;

use App\Support\SemanticGraph;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SemanticDatasetIntegrityTest extends TestCase
{
    #[Test]
    public function footwear_dataset_relations_reference_existing_concepts_and_valid_types(): void
    {
        /** @var array{concepts: list<array<string, mixed>>, relations: list<array{from: string, to: string, type: string}>} $dataset */
        $dataset = require base_path('database/seeders/Data/FootwearConstructionDataset.php');

        $conceptKeys = collect($dataset['concepts'])
            ->pluck('key')
            ->filter(fn (mixed $key): bool => is_string($key) && $key !== '')
            ->values()
            ->all();
        $known = array_fill_keys($conceptKeys, true);

        $missingRefs = [];
        $invalidTypes = [];
        $selfRefs = [];

        foreach ($dataset['relations'] as $relation) {
            $from = $relation['from'];
            $to = $relation['to'];
            $type = $relation['type'];

            if (! isset($known[$from]) || ! isset($known[$to])) {
                $missingRefs[] = "{$from}->{$to}";
            }
            if (! SemanticGraph::isAllowedStoredType($type)) {
                $invalidTypes[] = "{$from}->{$to}:{$type}";
            }
            if ($from === $to) {
                $selfRefs[] = "{$from}:{$type}";
            }
        }

        $this->assertSame([], $missingRefs, 'Dataset contains relation targets without concept nodes.');
        $this->assertSame([], $invalidTypes, 'Dataset contains unsupported relation types.');
        $this->assertSame([], $selfRefs, 'Dataset contains self-referential relations.');
    }

    #[Test]
    public function priority_concepts_carry_locale_native_editorial_notes(): void
    {
        /** @var array{concepts: list<array{key: string, translations: array<string, array{editorial_notes: ?string}>}>} $dataset */
        $dataset = require base_path('database/seeders/Data/FootwearConstructionDataset.php');

        $roughing = collect($dataset['concepts'])->firstWhere('key', 'roughing');
        $this->assertNotNull($roughing);

        $en = $roughing['translations']['en']['editorial_notes'] ?? '';
        $de = $roughing['translations']['de']['editorial_notes'] ?? '';

        $this->assertNotSame('', $en);
        $this->assertNotSame('', $de);
        $this->assertNotSame($en, $de);
        $this->assertStringContainsString('primer', strtolower($en));
        $this->assertStringContainsString('primer', strtolower($de));
        $this->assertStringContainsString('Aufrauen', $de);
    }

    #[Test]
    public function second_order_welt_prep_concepts_carry_locale_native_editorial_notes(): void
    {
        /** @var array{concepts: list<array{key: string, translations: array<string, array{editorial_notes: ?string}>}>} $dataset */
        $dataset = require base_path('database/seeders/Data/FootwearConstructionDataset.php');

        $gemming = collect($dataset['concepts'])->firstWhere('key', 'gemming-rib');
        $this->assertNotNull($gemming);

        $en = $gemming['translations']['en']['editorial_notes'] ?? '';
        $it = $gemming['translations']['it']['editorial_notes'] ?? '';

        $this->assertNotSame('', $en);
        $this->assertNotSame('', $it);
        $this->assertNotSame($en, $it);
        $this->assertStringContainsString('holdfast', strtolower($en));
        $this->assertStringContainsString('presa', strtolower($it));
        $this->assertStringContainsString('nervatura', strtolower($it));
    }
}

