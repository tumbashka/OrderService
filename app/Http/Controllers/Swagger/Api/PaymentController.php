<?php

namespace App\Http\Controllers\Swagger\Api;

use App\Http\Controllers\Controller;
use OpenApi\Attributes as OA;

class PaymentController extends Controller
{
    #[OA\Get(
        path: '/api/payment-types',
        description: 'Get payment types',
        summary: 'Payment types',
        tags: ['Payment'],
        responses: [
            new OA\Response(
                response: 200, description: 'Payment types', content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/PaymentTypeResource')
                        ),
                    ]
                )
            ),
        ]
    )]
    public function paymentTypes() {}

    #[OA\Get(
        path: '/api/orders/{order_id}/pay',
        description: 'Create payment to order with id {order_id}',
        summary: 'Create payment to order',
        tags: ['Payment'],
        parameters: [
            new OA\Parameter(
                name: 'order_id',
                description: 'Order ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer'),
                example: 2
            ),
        ],
        responses: [
            new OA\Response(
                response: 200, description: 'Payment data', content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            ref: '#/components/schemas/PaymentResource',
                            type: 'object'
                        ),
                    ]
                )
            ),
        ]
    )]
    public function createOrderPayment() {}

    #[OA\Post(
        path: '/api/orders/{order_id}/payment/refresh',
        description: 'Create new payment for order with id {order_id}',
        summary: 'Create new payment for order',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(ref: '#/components/schemas/RefreshPaymentRequest')
            )
        ),
        tags: ['Payment'],
        parameters: [
            new OA\Parameter(
                name: 'order_id',
                description: 'Order ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer'),
                example: 2
            ),
        ],
        responses: [
            new OA\Response(
                response: 200, description: 'Payment url', content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'paymentUrl', type: 'string'),
                    ]
                )
            ),
        ]
    )]
    public function refreshPayment() {}
}
