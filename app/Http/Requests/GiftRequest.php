<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GiftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:160'],
            'description' => ['nullable', 'string', 'max:2000'],
            'link' => ['nullable', 'url', 'max:500'],
            'is_list' => ['sometimes', 'boolean'],
        ];
    }

    public function normalized(): array
    {
        $validated = $this->validated();
        $validated['is_list'] = $this->boolean('is_list');

        return $validated;
    }
}
