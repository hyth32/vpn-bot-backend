<?php

namespace App\Jobs;

use App\Http\Integrations\RabbitMQService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;

class SendOrderStatusMessage implements ShouldQueue
{
    use Queueable, InteractsWithQueue, Dispatchable;

    public function __construct(
        public array $data,
    ) {}

    public function handle(RabbitMQService $rabbitMq): void
    {
        $rabbitMq->publish('payments', $this->data);
    }
}
