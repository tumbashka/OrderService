<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\YookassaService;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function yookassa(Request $request, YookassaService $service)
    {
        $service->changePaymentStatus($request);
    }
}
