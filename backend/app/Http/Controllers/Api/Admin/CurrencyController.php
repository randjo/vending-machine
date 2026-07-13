<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CurrencyRequest;
use App\Models\Currency;
use Illuminate\Http\JsonResponse;

final class CurrencyController extends Controller
{
    public function index()
    {
        return response()->json([
            'currency' => Currency::first()
        ]);
    }

    public function update(CurrencyRequest $request, int $id): JsonResponse
    {
        $currency = Currency::findOrFail($id);

        $currency->update([
            'sign' => $request->sign,
            'space' => $request->space,
            'position' => $request->position,
        ]);

        return response()->json([
            'message' => 'Currency updated',
            'drink' => $currency,
        ]);
    }
}
