<?php

namespace App\Http\Requests\Admin;

use App\Models\Concept;
use App\Support\Editorial\WorkflowStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

final class UpdateConceptRequest extends FormRequest
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
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $targetStatus = (string) $this->input('status');
            $domainIds = $this->input('domain_ids', []);
            if (in_array($targetStatus, [WorkflowStatus::REVIEW, WorkflowStatus::PUBLISHED], true) && (is_array($domainIds) ? count($domainIds) === 0 : true)) {
                $validator->errors()->add('domain_ids', __('Review and published concepts must belong to at least one domain.'));
            }

            /** @var Concept|null $concept */
            $concept = $this->route('concept');
            if (! $concept instanceof Concept) {
                return;
            }

            if ($targetStatus === WorkflowStatus::PUBLISHED) {
                $publishedTranslations = $concept->translations()
                    ->where('status', WorkflowStatus::PUBLISHED)
                    ->count();
                if ($publishedTranslations === 0) {
                    $validator->errors()->add('status', __('Published concepts require at least one published translation.'));
                }
            }
        });
    }
}
