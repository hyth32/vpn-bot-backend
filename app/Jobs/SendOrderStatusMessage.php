<?php

namespace App\Jobs;

use App\Http\Integrations\RabbitMQService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendOrderStatusMessage implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public array $data,
    ) {}

    public function handle(RabbitMQService $rabbitMq): void
    {
        $rabbitMq->publish('payments', $this->data);
    }
}
