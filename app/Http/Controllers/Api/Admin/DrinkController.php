<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Admin;

use App\DTO\Vending\DrinkData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDrinkRequest;
use App\Services\AdminVendingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

final class DrinkController extends Controller
{
    public function __construct(private AdminVendingService $service) {}

    public function store(StoreDrinkRequest $request): JsonResponse
    {
        $drink = $this->service->createDrink(DrinkData::fromRequest($request->validated()));

        return response()->json([
            'message' => 'Drink created',
            'drink' => $drink,
        ], 201);
    }

    public function destroy(int $id): JsonResponse
    {
        return response()->json([
            'message' => $this->service->deleteDrink($id) > 0 ? 'Drink deleted' : 'Drink not found',
        ]);
    }
}
