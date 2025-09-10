<?php

namespace App\Http\Controllers\Api;

use App\Data\OrderData;
use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderDetailedResource;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\CatalogService;
use App\Services\OrderService;
use App\Services\UserService;
use Illuminate\Support\Carbon;

class OrderController extends Controller
{
    public function userOrders(int $userId, UserService $userService)
    {
        $user = $userService->getUser($userId);
        if (!$user) {
            abort(404, 'User not found');
        }
        $orders = Order::where('user_id', $userId)->paginate();

        return OrderResource::collection($orders);
    }

    public function show(Order $order)
    {
        return OrderDetailedResource::make($order);
    }

    public function store(
        StoreOrderRequest $request,
        OrderService $orderService,
        UserService $userService,
        CatalogService $catalogService
    ) {
        $orderDTO = OrderData::from([
            'user' => $userService->getUser($request->input('user_id')),
            'car' => $catalogService->getCar($request->input('car_id')),
            'payment_id' => $request->input('payment_id'),
            'services' => $catalogService->getAdditionalServices($request->input('services')),
            'start_date' => Carbon::parse($request->input('start_date')),
            'end_date' => Carbon::parse($request->input('end_date')),
        ]);

        try {
            $paymentUrl = $orderService->createOrderAndGetPaymentURL($orderDTO);
        } catch (\Throwable $exception) {
            return response()->json([
                'result' => false,
                'message' => $exception->getMessage(),
            ],
                422
            );
        }

        return response()->json(compact('paymentUrl'));
    }

    public function reservedDates(int $carId, OrderService $orderService, CatalogService $catalogService)
    {
        $carExist = $catalogService->checkCar($carId);
        if (!$carExist) {
            abort(404, 'Car not found');
        }

        return response()->json([
            'data' => $orderService->getReservedDates($carId, true),
        ]);
    }

    public function cancel(Order $order)
    {
        $order->update([
            'status' => OrderStatus::Canceled,
        ]);

        return response()->json([
            'status' => OrderStatus::Canceled->name,
        ]);
    }
}
