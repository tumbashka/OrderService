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
use App\Services\UserService;
use App\Services\OrderService;
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

    public function store(StoreOrderRequest $request, int $carId, OrderService $orderService, CatalogService  $catalogService)
    {
        $car = $catalogService->getCar($carId);
        dd($car);

        $orderDTO = OrderData::from([
            'car' => $carId,
            'payment_id' => $request->input('payment_id'),
            'services' => $request->input('services'),
            'start_date' => Carbon::parse($request->input('start_date')),
            'end_date' => Carbon::parse($request->input('end_date')),
            'user' => $request->user(),
        ]);

        return response()->json([
            'paymentUrl' => $orderService->createOrderAndGetPaymentURL($orderDTO),
        ]);
    }

    public function reservedDates(Car $car, OrderService $orderService)
    {
        return response()->json([
            'data' => $orderService->getReservedDates($car),
        ]);
    }

    public function cancel(Order $order)
    {
        $order->update([
            'status' => OrderStatus::Canceled->value,
        ]);

        return response()->json([
            'status' => OrderStatus::Canceled->name,
        ]);
    }
}
