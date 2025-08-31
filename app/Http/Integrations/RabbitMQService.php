<?php

namespace App\Http\Integrations;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService
{
    const CONNECTION_PERSISTENT = 2;

    protected AMQPStreamConnection $connection;
    protected $channel;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(
            config('rabbitmq.host'),
            config('rabbitmq.port'),
            config('rabbitmq.user'),
            config('rabbitmq.password'),
            config('rabbitmq.vhost'),
        );

        $this->channel = $this->connection->channel();
    }

    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }

    public function publish(string $queueName, array $data)
    {
        $this->channel->queue_declare($queueName, false, true, false, false);

        $message = new AMQPMessage(json_encode($data), [
            'content_type' => 'application/json',
            'delivery_mode' => self::CONNECTION_PERSISTENT,
        ]);

        $this->channel->basic_publish($message, '', $queueName);
    }
}
