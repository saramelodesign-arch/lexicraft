<?php

namespace App\Http\Requests\Admin;

use App\Models\ConceptTranslation;
use App\Support\Editorial\EditorialQualityGuard;
use App\Support\Editorial\SlugGovernance;
use App\Support\Editorial\WorkflowStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

final class UpdateConceptTranslationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        /** @var ConceptTranslation|null $translation */
        $translation = $this->route('translation');
        $languageId = $translation?->language_id ?? 0;

        return [
            'status' => ['required', Rule::in(WorkflowStatus::all())],
            'term' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:'.SlugGovernance::MAX_LENGTH,
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('concept_translations', 'slug')
                    ->where(fn ($q) => $q->where('language_id', $languageId))
                    ->ignore($translation?->id),
            ],
            'short_definition' => ['nullable', 'string'],
            'full_definition' => ['nullable', 'string'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string'],
            'meta_keywords' => ['nullable', 'string'],
            'industry_notes' => ['nullable', 'string'],
            'seo_canonical_url' => ['nullable', 'string', 'max:2048', 'url'],
            'og_title' => ['nullable', 'string', 'max:512'],
            'og_description' => ['nullable', 'string'],
            'examples' => ['nullable', 'array'],
            'examples.*.id' => ['nullable', 'integer'],
            'examples.*.example' => ['nullable', 'string'],
            'examples.*.context' => ['nullable', 'string', 'max:64'],
            'examples.*.sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => SlugGovernance::normalize((string) $this->input('slug')),
        ]);
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $examples = $this->input('examples', []);
            $rows = is_array($examples) ? $examples : [];

            try {
                EditorialQualityGuard::assertTranslationQuality(
                    (string) $this->input('term'),
                    $this->input('short_definition'),
                    $this->input('full_definition'),
                    $rows,
                    (string) $this->input('status')
                );
            } catch (\Illuminate\Validation\ValidationException $e) {
                foreach ($e->errors() as $field => $messages) {
                    foreach ($messages as $message) {
                        $validator->errors()->add($field, $message);
                    }
                }
            }
        });
    }
}
