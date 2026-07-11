<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Admin;

use App\DTO\Vending\CoinData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCoinRequest;
use App\Http\Resources\CoinResource;
use App\Services\AdminVendingService;
use Illuminate\Http\JsonResponse;

final class CoinController extends Controller
{
    public function __construct(private AdminVendingService $service) {}

    public function index(): JsonResponse
    {
        return response()->json([
            'coins' => CoinResource::collection($this->service->getAllCoins()),
        ]);
    }

    public function store(StoreCoinRequest $request): JsonResponse
    {
        $coin = $this->service->createCoin(CoinData::preparedValue($request->validated()));

        return response()->json([
            'message' => 'Coin created',
            'coin' => $coin,
        ], 201);
    }

    public function update(StoreCoinRequest $request, int $id): JsonResponse
    {
        $coin = $this->service->updateCoin($id, CoinData::preparedValue($request->validated()));

        return response()->json([
            'message' => 'Coin updated',
            'coin' => $coin,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        return response()->json([
            'message' => $this->service->deleteCoin($id) > 0 ? 'Coin deleted' : 'Coin not found',
        ]);
    }
}
