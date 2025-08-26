<?php

namespace App\Http\Integrations;

use App\Http\Requests\YooKassa\YooKassaWebhookRequest;
use Illuminate\Http\Request;

class YooKassaCallbackController
{
    // public function handle(YooKassaWebhookRequest $request)
    public function handle(Request $request)
    {
        info("WEBHOOK DATA" . $request->getContent());
    }
}
