<?php

namespace App\Services;

use App\Data\OrderPaymentData;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Interfaces\PaymentInterface;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use YooKassa\Client;
use YooKassa\Model\CurrencyCode;
use YooKassa\Model\Payment\ConfirmationType;
use YooKassa\Request\Payments\CreatePaymentRequest;

class YookassaService implements PaymentInterface
{
    public function __construct(
        private $client = new Client
    ) {
        $this->client->getApiClient()->setConfig(['url' => 'http://localhost:777']);
        $this->client->setAuth(config('yookassa.shopId'), config('yookassa.secretKey'));
    }

    public function createPayment(OrderPaymentData $paymentDTO): Payment
    {
        $builder = CreatePaymentRequest::builder();
        $builder->setAmount($paymentDTO->amount / 100)
            ->setCurrency(CurrencyCode::RUB)
            ->setCapture(true)
            ->setDescription("Оплата заказа №:{$paymentDTO->order->id}")
            ->setConfirmation([
                'type' => ConfirmationType::REDIRECT,
                'returnUrl' => route('order.show', ['order' => $paymentDTO->order->id]),
            ]);

        $request = $builder->build();

        $response = $this->client->createPayment($request);

        return Payment::create([
            'order_id' => $paymentDTO->order->id,
            'type' => PaymentType::Yookassa,
            'amount' => $response->amount->getValue(),
            'service_payment_id' => $response->getId(),
            'status' => PaymentStatus::fromYookassaStatus($response->getStatus()),
            'url' => $response->getConfirmation()->getConfirmationUrl(),
        ]);
    }

    public function changePaymentStatus(Request $request): void
    {
        if (! isset($request['object'])) {
            return;
        }
        $object = $request['object'];
        $yookassaStatus = $object['status'];

        $status = PaymentStatus::fromYookassaStatus($yookassaStatus);
        if (! $status) {
            Log::error("Платежная система передала неизвестный статус:{$yookassaStatus}");

            return;
        }

        $payment = Payment::firstWhere('service_payment_id', $object['id']);
        if (! $payment) {
            Log::error("Платеж с service_payment_id:{$object['id']} не найден");

            return;
        }

        $payment->status = $status;
        $payment->update();
    }
}
