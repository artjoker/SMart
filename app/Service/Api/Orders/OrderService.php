<?php

namespace App\Service\Api\Orders;

use App\Models\Order;

class OrderService
{
    public function getById(
        string $order_id
    ): Order {
        return Order::query()
            ->where('id', $order_id)
            ->with([
                'products',
                'client',
            ])
            ->withSum('products', 'price')
            ->first();
    }
}
