<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Messengers\Telegram\TelegramWebhookHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TelegramWebhookController extends Controller
{
    public function __construct(
        private readonly TelegramWebhookHandler $handler,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $secret = (string) config(
            'services.telegram.webhook_secret'
        );

        $receivedSecret = (string) $request->header(
            'X-Telegram-Bot-Api-Secret-Token'
        );

        abort_if(
            $secret === '' || ! hash_equals($secret, $receivedSecret),
            403
        );

        $this->handler->handle($request->all());

        return response()->json([
            'ok' => true,
        ]);
    }
}
