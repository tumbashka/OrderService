<?php

namespace App\Services;

use App\Data\AdditionalServiceData;
use App\Data\OrderData;
use App\Data\OrderPaymentData;
use App\Enums\OrderStatus;
use App\Enums\PaymentType;
use App\Enums\ProductType;
use App\Interfaces\PaymentInterface;
use App\Models\Order;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;

class OrderService
{
    /**
     * @throws Throwable
     */
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

    /**
     * @throws Throwable
     */
    public function createOrder(OrderData $orderDTO): Order
    {
        return DB::transaction(function () use ($orderDTO) {
            $order = Order::create([
                'status' => OrderStatus::NeedPayment->value,
                'rent_start' => $orderDTO->start_date,
                'rent_end' => $orderDTO->end_date,
                'user_id' => $orderDTO->user->id,
            ]);

            $order->products()->create([
                'price' => $orderDTO->car->price,
                'name' => $orderDTO->car->name,
                'product_id' => $orderDTO->car->id,
                'product_type' => ProductType::Car,
            ]);

            $services = $orderDTO->services;
            /** @var $service AdditionalServiceData */
            foreach ($services as $service) {
                $order->products()->create([
                    'price' => $service->price,
                    'name' => $service->name,
                    'product_id' => $service->id,
                    'product_type' => ProductType::AdditionalService,
                ]);
            }

            return $order;
        });
    }

    /**
     * @throws Exception
     */
    private function getPaymentService($paymentId): PaymentInterface
    {
        return match ($paymentId) {
            PaymentType::Yookassa->value => new YookassaService,
            default => throw new Exception("Not supported payment service: {$paymentId}"),
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
