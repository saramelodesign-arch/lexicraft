<?php

namespace App\Http\Requests\Admin;

use App\Models\Language;
use App\Support\Editorial\EditorialQualityGuard;
use App\Support\Editorial\SlugGovernance;
use App\Support\Editorial\TerminologyStatus;
use App\Support\Editorial\WorkflowStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

final class StoreConceptTranslationRequest extends FormRequest
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
            'language_id' => ['required', 'exists:languages,id'],
            'status' => ['required', Rule::in(WorkflowStatus::all())],
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
            'terminology_status' => ['nullable', Rule::in(TerminologyStatus::all())],
            'validated_at' => ['nullable', 'date'],
            'validated_by' => ['nullable', 'integer', Rule::exists('users', 'id')->where(fn ($q) => $q->where('is_admin', true))],
            'editorial_notes' => ['nullable', 'string'],
            'source_reference_text' => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => SlugGovernance::normalize((string) $this->input('slug')),
            'terminology_status' => $this->input('terminology_status', TerminologyStatus::DRAFT),
        ]);
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $language = Language::query()->find($this->integer('language_id'));
            if ($language === null || ! $language->is_active) {
                $validator->errors()->add('language_id', __('admin.msg_choose_active_language'));
            }

            try {
                EditorialQualityGuard::assertTranslationQuality(
                    (string) $this->input('term'),
                    $this->input('short_definition'),
                    $this->input('full_definition'),
                    [],
                    (string) $this->input('status')
                );
            } catch (\Illuminate\Validation\ValidationException $e) {
                foreach ($e->errors() as $field => $messages) {
                    foreach ($messages as $message) {
                        $validator->errors()->add($field, $message);
                    }
                }
            }

            $validatedAt = $this->input('validated_at');
            $validatedBy = $this->input('validated_by');
            if (($validatedAt === null) xor ($validatedBy === null)) {
                $validator->errors()->add('validated_at', __('admin.msg_validation_metadata_pair'));
            }

            $terminologyStatus = (string) $this->input('terminology_status');
            if ($terminologyStatus === TerminologyStatus::VALIDATED && ($validatedAt === null || $validatedBy === null)) {
                $validator->errors()->add('terminology_status', __('admin.msg_validated_requires_metadata'));
            }

            $workflowStatus = (string) $this->input('status');
            if (! TerminologyStatus::isWorkflowCoherent($terminologyStatus, $workflowStatus)) {
                $validator->errors()->add('terminology_status', 'Terminology status is not coherent with translation workflow state.');
            }

            if (TerminologyStatus::requiresEditorialContext($terminologyStatus)) {
                $hasContext = filled((string) $this->input('editorial_notes'))
                    || filled((string) $this->input('source_reference_text'));
                if (! $hasContext) {
                    $validator->errors()->add('editorial_notes', 'Regional or deprecated terminology requires editorial context or source reference.');
                }
            }
        });
    }
}
