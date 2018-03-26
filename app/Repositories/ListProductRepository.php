<?php
/**
 * Created by PhpStorm.
 * User: Pichau
 * Date: 26/03/2018
 * Time: 12:46
 */

namespace App\Repositories;


use App\ListProduct;

class ListProductRepository
{
    /**
     * @param int $list
     * @param int $product
     * @param int $quantity
     * @param float $price
     */
    public function create(int $list, int $product, int $quantity, float $price)
    {
        ListProduct::query()->create([
            'list_id' => $list,
            'product_id' => $product,
            'quantity' => $quantity,
            'unit_price' => $price
        ]);
    }
}