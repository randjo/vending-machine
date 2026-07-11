<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Domain\Vending\Currency;
use Illuminate\Foundation\Http\FormRequest;

final class PutCoinRequest extends FormRequest
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
                'in:0.05, 0.10, 0.20, 0.50, 1, 2'
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'value' => Currency::fromCents($this->value),
        ]);
    }
}
