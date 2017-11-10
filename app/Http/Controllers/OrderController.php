<?php

namespace App\Http\Controllers;

use App\Order;

class OrderController
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll()
    {
        return Order::with(['customer:id,name'])->get();
    }

    /**
     * @param $id
     * @return static
     */
    public function getOne($id)
    {
        return Order::with(['customer:id,name,phone,address', 'orderProduct', 'orderProduct.product'])->where('id', '=', $id)->first();
    }
}