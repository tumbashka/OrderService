<?php

namespace App\Http\Controllers\Api;

use App\Data\OrderPaymentData;
use App\Enums\PaymentType;
use App\Http\Controllers\Controller;
use App\Http\Requests\RefreshPaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Http\Resources\PaymentTypeResource;
use App\Models\Order;
use App\Services\OrderService;
use App\Services\YookassaService;

class PaymentController extends Controller
{
    public function paymentTypes()
    {
        $paymentTypes = PaymentType::cases();

        return PaymentTypeResource::collection($paymentTypes);
    }

    public function createOrderPayment(
        Order $order,
        YookassaService $yookassaService,
    ) {
        $orderDTO = OrderPaymentData::from([
            'order' => $order,
            'amount' => $order->payment_amount,
        ]);
        $payment = $yookassaService->createPayment($orderDTO);

        return PaymentResource::make($payment);
    }

    public function refreshPayment(
        RefreshPaymentRequest $request,
        Order $order,
        OrderService $orderService
    ) {
        $orderDTO = OrderPaymentData::from([
            'order' => $order,
            'amount' => $order->payment_amount,
            'paymentId' => $request->input('paymentId'),
        ]);

        return response()->json([
            'paymentUrl' => $orderService->refreshPayment($orderDTO),
        ]);
    }
}
