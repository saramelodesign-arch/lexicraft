<?php

namespace Tests\Unit;

use App\Support\FootwearConstructionMediaAuthority;
use App\Support\TechnicalFootwearDiagramSvg;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FootwearConstructionMediaAuthorityTest extends TestCase
{
    #[Test]
    public function priority_concepts_have_two_authority_media_slots_each(): void
    {
        foreach (FootwearConstructionMediaAuthority::PRIORITY_CONCEPT_KEYS as $key) {
            $slots = FootwearConstructionMediaAuthority::mediaSlotsFor($key);
            $this->assertCount(2, $slots, "Expected two media slots for {$key}");
            $this->assertSame('diagram', $slots[0]['kind'] ?? null);
            $this->assertContains($slots[0]['semantic_role'] ?? '', ['workflow', 'construction'], true);
        }
    }

    #[Test]
    public function recommended_visuals_are_localized_for_priority_concepts(): void
    {
        foreach (['en', 'pt', 'fr', 'de', 'it', 'es'] as $locale) {
            $lasting = FootwearConstructionMediaAuthority::recommendedVisuals('lasting', $locale);
            $this->assertNotEmpty($lasting, "Missing lasting recommendations for {$locale}");
        }
    }

    #[Test]
    public function technical_diagram_svg_renders_for_each_priority_concept(): void
    {
        foreach (FootwearConstructionMediaAuthority::PRIORITY_CONCEPT_KEYS as $key) {
            foreach (TechnicalFootwearDiagramSvg::variantsForConcept($key) as $variant) {
                $svg = TechnicalFootwearDiagramSvg::render($key, $variant);
                $this->assertStringContainsString('<svg', $svg);
                $this->assertStringContainsString('</svg>', $svg);
            }
        }
    }
}
