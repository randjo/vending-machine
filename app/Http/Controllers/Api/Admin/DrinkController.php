<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Admin;

use App\DTO\Vending\DrinkData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDrinkRequest;
use App\Http\Resources\DrinkResource;
use App\Services\AdminVendingService;
use Illuminate\Http\JsonResponse;

final class DrinkController extends Controller
{
    public function __construct(private AdminVendingService $service) {}

    public function index(): JsonResponse
    {
        return response()->json([
            'drinks' => DrinkResource::collection($this->service->getAllDrinks()),
        ]);
    }

    public function store(StoreDrinkRequest $request): JsonResponse
    {
        $drink = $this->service->createDrink(DrinkData::fromRequest($request->validated()));

        return response()->json([
            'message' => 'Drink created',
            'drink' => $drink,
        ], 201);
    }

    public function update(StoreDrinkRequest $request, int $id): JsonResponse
    {
        $drink = $this->service->updateDrink($id, DrinkData::fromRequest($request->validated()));

        return response()->json([
            'message' => 'Drink updated',
            'drink' => $drink,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        return response()->json([
            'message' => $this->service->deleteDrink($id) > 0 ? 'Drink deleted' : 'Drink not found',
        ]);
    }
}
