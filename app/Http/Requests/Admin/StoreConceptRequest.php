<?php

namespace App\Http\Requests\Admin;

use App\Models\Language;
use App\Support\Editorial\EditorialQualityGuard;
use App\Support\Editorial\SlugGovernance;
use App\Support\Editorial\WorkflowStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

final class StoreConceptRequest extends FormRequest
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
        return [
            'status' => ['required', Rule::in(WorkflowStatus::all())],
            'difficulty_level' => ['nullable', 'string', 'max:64'],
            'is_featured' => ['sometimes', 'boolean'],
            'domain_ids' => ['nullable', 'array'],
            'domain_ids.*' => ['integer', 'exists:domains,id'],
            'language_id' => ['required', 'exists:languages,id'],
            'term' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:'.SlugGovernance::MAX_LENGTH,
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('concept_translations', 'slug')->where(
                    fn ($q) => $q->where('language_id', (int) $this->integer('language_id')),
                ),
            ],
            'short_definition' => ['nullable', 'string'],
            'full_definition' => ['nullable', 'string'],
            'translation_status' => ['nullable', Rule::in(WorkflowStatus::all())],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => SlugGovernance::normalize((string) $this->input('slug')),
            'translation_status' => $this->input('translation_status', $this->input('status', WorkflowStatus::DRAFT)),
        ]);
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $language = Language::query()->find($this->integer('language_id'));
            if ($language === null || ! $language->is_active) {
                $validator->errors()->add('language_id', __('Choose an active language.'));
            }

            $domainIds = $this->input('domain_ids', []);
            if (
                in_array((string) $this->input('status'), [WorkflowStatus::REVIEW, WorkflowStatus::PUBLISHED], true)
                && (is_array($domainIds) ? count($domainIds) === 0 : true)
            ) {
                $validator->errors()->add('domain_ids', __('Review and published concepts must belong to at least one domain.'));
            }

            try {
                EditorialQualityGuard::assertTranslationQuality(
                    (string) $this->input('term'),
                    $this->input('short_definition'),
                    $this->input('full_definition'),
                    [],
                    (string) $this->input('translation_status')
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
