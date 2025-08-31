<?php

namespace App\Http\Integrations;

use App\Http\Requests\YooKassa\YooKassaWebhookRequest;
use App\Jobs\SendOrderStatusMessage;

class YooKassaCallbackController
{
    public function handle(YooKassaWebhookRequest $request)
    {
        info("WEBHOOK DATA" . $request->getContent() . '\n');

        $data = $request->validated();

        info('PARSED DATA: ' . json_encode($data) . '\n');

        dispatch(new SendOrderStatusMessage([
            'success' => true,
        ]));
    }
}
