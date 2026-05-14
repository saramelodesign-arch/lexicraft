<?php

namespace Tests\Feature;

use App\Models\Concept;
use App\Models\ConceptTranslation;
use App\Models\Language;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileUnacceptableForCollection;
use Tests\TestCase;

class ConceptMediaTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function concept_page_renders_featured_image_og_tags_and_schema(): void
    {
        $en = Language::query()->create([
            'code' => 'en',
            'name' => 'English',
            'native_name' => 'English',
            'flag_icon' => null,
            'is_active' => true,
        ]);

        $concept = Concept::query()->create([
            'status' => 'published',
            'difficulty_level' => null,
            'is_featured' => false,
        ]);

        ConceptTranslation::query()->create([
            'concept_id' => $concept->id,
            'language_id' => $en->id,
            'term' => 'Lasting',
            'slug' => 'lasting',
            'short_definition' => 'Pulling the upper onto the last.',
            'full_definition' => null,
            'seo_title' => null,
            'seo_description' => null,
            'meta_keywords' => null,
            'industry_notes' => null,
        ]);

        $path = $this->tempPngPath();
        $concept->addMedia($path)
            ->preservingOriginal()
            ->withCustomProperties([
                'locales' => [
                    'en' => [
                        'title' => 'Lasting operation',
                        'alt' => 'Upper being lasted on a shoe last',
                        'caption' => 'Industrial lasting reference.',
                    ],
                ],
            ])
            ->toMediaCollection(Concept::COLLECTION_FEATURED);

        $response = $this->get(route('glossary.concept', ['locale' => 'en', 'slug' => 'lasting']));
        $response->assertOk();
        $response->assertSee('Featured image', false);
        $response->assertSee('property="og:image"', false);
        $response->assertSee('"image":', false);
    }

    #[Test]
    public function gallery_splits_diagram_kind_and_video_embed_renders_iframe(): void
    {
        $en = Language::query()->create([
            'code' => 'en',
            'name' => 'English',
            'native_name' => 'English',
            'flag_icon' => null,
            'is_active' => true,
        ]);

        $concept = Concept::query()->create([
            'status' => 'published',
            'difficulty_level' => null,
            'is_featured' => false,
        ]);

        ConceptTranslation::query()->create([
            'concept_id' => $concept->id,
            'language_id' => $en->id,
            'term' => 'Bench process',
            'slug' => 'bench-process',
            'short_definition' => 'Test',
            'full_definition' => null,
            'seo_title' => null,
            'seo_description' => null,
            'meta_keywords' => null,
            'industry_notes' => null,
        ]);

        $png = $this->tempPngPath();
        $concept->addMedia($png)
            ->preservingOriginal()
            ->withCustomProperties([
                'locales' => ['en' => ['title' => 'Photo', 'alt' => 'Photo alt', 'caption' => '']],
                'kind' => 'photo',
            ])
            ->toMediaCollection(Concept::COLLECTION_GALLERY);

        $concept->addMedia($png)
            ->preservingOriginal()
            ->withCustomProperties([
                'locales' => ['en' => ['title' => 'Diagram', 'alt' => 'Diagram alt', 'caption' => 'CAD overlay']],
                'kind' => 'diagram',
            ])
            ->toMediaCollection(Concept::COLLECTION_GALLERY);

        $concept->addMedia($png)
            ->preservingOriginal()
            ->withCustomProperties([
                'locales' => ['en' => ['title' => 'Process clip', 'alt' => 'Video poster', 'caption' => '']],
                'embed_url' => 'https://www.youtube-nocookie.com/embed/placeholder',
            ])
            ->toMediaCollection(Concept::COLLECTION_VIDEOS);

        $r = $this->get(route('glossary.concept', ['locale' => 'en', 'slug' => 'bench-process']));
        $r->assertOk();
        $r->assertSee('Gallery', false);
        $r->assertSee('Technical diagrams', false);
        $r->assertSee('Videos', false);
        $r->assertSee('youtube-nocookie.com/embed', false);
    }

    #[Test]
    public function featured_collection_rejects_non_image_mime_types(): void
    {
        $this->expectException(FileUnacceptableForCollection::class);

        $en = Language::query()->create([
            'code' => 'en',
            'name' => 'English',
            'native_name' => 'English',
            'flag_icon' => null,
            'is_active' => true,
        ]);

        $concept = Concept::query()->create([
            'status' => 'published',
            'difficulty_level' => null,
            'is_featured' => false,
        ]);

        ConceptTranslation::query()->create([
            'concept_id' => $concept->id,
            'language_id' => $en->id,
            'term' => 'X',
            'slug' => 'x',
            'short_definition' => null,
            'full_definition' => null,
            'seo_title' => null,
            'seo_description' => null,
            'meta_keywords' => null,
            'industry_notes' => null,
        ]);

        $txt = tempnam(sys_get_temp_dir(), 'lex').'.txt';
        file_put_contents($txt, 'not an image');

        try {
            $concept->addMedia($txt)->toMediaCollection(Concept::COLLECTION_FEATURED);
        } finally {
            @unlink($txt);
        }
    }

    private function tempPngPath(): string
    {
        $path = tempnam(sys_get_temp_dir(), 'lex').'.png';
        $im = imagecreatetruecolor(40, 30);
        imagecolorallocate($im, 200, 200, 200);
        imagepng($im, $path);
        imagedestroy($im);

        return $path;
    }
}
