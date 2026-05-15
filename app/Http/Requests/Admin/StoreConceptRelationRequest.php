<?php

namespace App\Http\Requests\Admin;

use App\Support\Locales;
use App\Support\SemanticGraph;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreConceptRelationRequest extends FormRequest
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
            'relation_type' => ['required', 'string', Rule::in(SemanticGraph::STORED_TYPES)],
            'related_locale' => ['required', 'string', Rule::in(Locales::codes())],
            'related_slug' => ['required', 'string', 'max:255'],
        ];
    }
}
