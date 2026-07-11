<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreCoinRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'value' => [
                'required',
                'numeric',
                'in:5, 10, 20, 50, 100, 200',
                Rule::unique('coins', 'value')->ignore($this->route('coin'))
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'value' => (int) round(((float) $this->value) * 100),
        ]);
    }
}
