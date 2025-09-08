<?php

namespace App\Services;

use App\Data\OrderData;
use App\Data\OrderPaymentData;
use App\Enums\OrderStatus;
use App\Enums\PaymentType;
use App\Interfaces\PaymentInterface;
use App\Models\AdditionalService;
use App\Models\Car;
use App\Models\Order;

class OrderService
{
    public function createOrderAndGetPaymentURL(OrderData $orderDTO): string
    {
        $order = $this->createOrder($orderDTO);
        $paymentService = $this->getPaymentService($orderDTO->payment_id);

        $paymentDTO = OrderPaymentData::from([
            'order' => $order,
            'amount' => $order->payment_amount,
        ]);

        $payment = $paymentService->createPayment($paymentDTO);

        return $payment->url;
    }

    public function createOrder(OrderData $orderDTO): Order
    {
        $order = Order::create([
            'status' => OrderStatus::NeedPayment->value,
            'rent_start' => $orderDTO->start_date,
            'rent_end' => $orderDTO->end_date,
            'user_id' => $orderDTO->user_id,
        ]);

        $order->cars()
            ->attach(
                $orderDTO->car_id,
                [
                    'price' => $orderDTO->car->getRawOriginal('price'),
                    'name' => $orderDTO->car->name,
                ]
            );

        $services = AdditionalService::findMany($orderDTO->services);

        foreach ($services as $service) {
            $order->additionalServices()
                ->attach(
                    $service,
                    [
                        'price' => $service->getRawOriginal('price'),
                        'name' => $service->name,
                    ]
                );
        }

        return $order;
    }

    private function getPaymentService($paymentId): PaymentInterface
    {
        return match ($paymentId) {
            PaymentType::Yookassa->value => new YookassaService,
            default => throw new \Exception("Not supported payment service: {$paymentId}"),
        };
    }

    public function getReservedDates(int $carId)
    {
        $orders = Order::query()->where('');
        $dates = collect();

        foreach ($orders as $order) {
            $reservedDates = $this->getOrderReservationDates($order);
            $dates = $dates->merge($reservedDates);
        }

        return $dates->sort()->values();
    }

    private function getOrderReservationDates(Order $order)
    {
        $dates = collect();
        $start = $order->rent_start->copy();
        $end = $order->rent_end->copy();

        while ($start->lte($end)) {
            $dates->add($start->toDateString());
            $start = $start->addDay();
        }

        return $dates;
    }

    public function refreshPayment(OrderPaymentData $orderDTO): string
    {
        $paymentService = $this->getPaymentService($orderDTO->paymentId);
        $payment = $paymentService->createPayment($orderDTO);

        return $payment->url;
    }
}
