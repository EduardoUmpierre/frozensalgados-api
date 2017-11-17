<?php

namespace App\Http\Controllers;

use App\Order;
use App\Customer;
use App\OrderProduct;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController
{
    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll(Request $request)
    {
        $order = $request->input('order');

        return Order::with(['customer:id,name'])->orderBy('created_at', $order ? $order : 'ASC')->get();
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function getOne($id)
    {
        return Order::with(['customer:id,name,phone,address', 'orderProduct', 'orderProduct.product'])->findOrFail($id);
    }

    public function create(Request $request)
    {
        $data = $request->all();

        $customerId = $data['customer'];
        $customer = Customer::query()->firstOrFail(['id']);

        $products = $data['order'];

        if ($customer) {
            $orderTotal = 0;

            foreach ($products as $key => $val) {
                $price = Product::query()->findOrFail($val['id'], ['price'])->first()->price;

                $products[$key]['price'] = $price;

                $orderTotal += $price * $val['qnt'];
            }

            $order = Order::query()->create(['customer_id' => $customerId, 'total' => $orderTotal]);

            foreach ($products as $key => $val) {
                OrderProduct::query()->create([
                    'order_id' => $order->id,
                    'product_id' => $val['id'],
                    'quantity' => $val['qnt'],
                    'unit_price' => $val['price']
                ]);
            }

            return response()->json(null, 201);
        }
    }
}
