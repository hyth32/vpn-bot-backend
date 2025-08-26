<?php

namespace App\Http\Integrations;

use App\Http\Requests\YooKassa\YooKassaWebhookRequest;

class YooKassaCallbackController
{
    public function handle(YooKassaWebhookRequest $request)
    {
        $data = $request->validated();
        info("WEBHOOK DATA" . json_encode($data));
    }
}
