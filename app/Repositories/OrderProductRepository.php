<?php

namespace App\Repositories;

use App\OrderProduct;

class OrderProductRepository
{
    /**
     * @param int $order
     * @param int $product
     * @param int $quantity
     * @param float $price
     */
    public function create(int $order, int $product, int $quantity, float $price)
    {
        OrderProduct::query()->create([
            'order_id' => $order,
            'product_id' => $product,
            'quantity' => $quantity,
            'unit_price' => $price
        ]);
    }
}