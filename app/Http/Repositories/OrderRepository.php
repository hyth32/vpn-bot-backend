<?php

namespace App\Http\Repositories;

use App\Models\Order;

class OrderRepository
{
    public function getByExternalId(string $externalId): ?Order
    {
        return Order::where('external_id', $externalId)->firstOrFail();
    }

    public function setPaidStatus(string $externalId): Order
    {
        $order = $this->getByExternalId($externalId);
        $order->update(['paid' => true]);
        return $order;
    }
}
