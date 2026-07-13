<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Domain\Vending\Currency;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class CurrencyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sign' => [
                'required',
                'string',
                'max:10',
            ],
            'space' => [
                'nullable',
                'string',
                'max:10',
            ],
            'position' => [
                'required',
                'string',
                'max:6',
                Rule::in([Currency::CURRENCY_POSITION_BEFORE, Currency::CURRENCY_POSITION_AFTER])
            ],
        ];
    }
}
