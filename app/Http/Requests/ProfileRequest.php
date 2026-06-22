<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'is_child' => ['sometimes', 'boolean'],
            'avatar' => ['required', 'integer', 'min:1', 'max:15'],
            'birthday' => ['nullable', 'date', 'before_or_equal:today', 'after_or_equal:1930-01-01'],
            'size_top' => ['nullable', 'string', 'max:80'],
            'size_bottom' => ['nullable', 'string', 'max:80'],
            'size_feet' => ['nullable', 'string', 'max:80'],
            'parent_ids' => ['array'],
            'parent_ids.*' => ['integer', 'exists:profiles,id'],
        ];
    }

    public function normalized(): array
    {
        $validated = $this->validated();
        $validated['is_child'] = $this->boolean('is_child');
        $validated['parent_ids'] = $validated['parent_ids'] ?? [];

        return $validated;
    }
}
