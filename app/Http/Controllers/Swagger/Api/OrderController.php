<?php

namespace App\Http\Controllers\Swagger\Api;

use App\Http\Controllers\Controller;
use OpenApi\Attributes as OA;

class OrderController extends Controller
{
    #[OA\Get(
        path: '/api/orders',
        description: 'Get orders',
        summary: 'Get orders',
        security: [
            [
                'bearerAuth' => [],
            ],
        ],
        tags: ['Order'],
        responses: [
            new OA\Response(
                response: 200, description: 'Order list', content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/OrderResource')
                        ),
                    ]
                )
            ),
        ]
    )]
    public function index() {}

    #[OA\Get(
        path: '/api/orders/{order_id}',
        description: 'Show order with id {order_id}',
        summary: 'Show order',
        security: [
            [
                'bearerAuth' => [],
            ],
        ],
        tags: ['Order'],
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
                response: 200, description: 'Order data', content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            ref: '#/components/schemas/OrderResource',
                            type: 'object'
                        ),
                    ]
                )
            ),
        ]
    )]
    public function show() {}

    #[OA\Post(
        path: '/api/orders/create/{car_id}',
        description: 'Create order for car with id {car_id}',
        summary: 'Create order',
        security: [
            [
                'bearerAuth' => [],
            ],
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(ref: '#/components/schemas/StoreOrderRequest')
            )
        ),
        tags: ['Order'],
        parameters: [
            new OA\Parameter(
                name: 'car_id',
                description: 'Car ID',
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
    public function store() {}

    #[OA\Get(
        path: '/api/orders/reserved-dates/{car_id}',
        description: 'Get reserved dates for car with id {car_id}',
        summary: 'Get car reserved dates',
        security: [
            [
                'bearerAuth' => [],
            ],
        ],
        tags: ['Order'],
        parameters: [
            new OA\Parameter(
                name: 'car_id',
                description: 'Car ID',
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
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(type: 'string')
                        ),
                    ]
                )
            ),
        ]
    )]
    public function reservedDates() {}

    #[OA\Post(
        path: '/api/orders/{order_id}/cancel',
        description: 'Cancel order with id {order_id}',
        summary: 'Cancel order',
        security: [
            [
                'bearerAuth' => [],
            ],
        ],
        tags: ['Order'],
        parameters: [
            new OA\Parameter(
                name: 'order_id',
                description: 'Order ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer'),
                example: 74
            ),
        ],
        responses: [
            new OA\Response(
                response: 200, description: 'Status', content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'status',
                            type: 'string',
                        ),
                    ]
                )
            ),
        ]
    )]
    public function cancel() {}
}
