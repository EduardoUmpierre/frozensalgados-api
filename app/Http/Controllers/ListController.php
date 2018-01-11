<?php

namespace App\Http\Controllers;

use App\Customer;
use App\ListModel;
use App\ListProduct;
use App\Product;
use Illuminate\Http\Request;

class ListController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
//        $this->validate($request, [
//            'title' => 'required',
//            'customer' => 'required',
//            'user' => 'required'
//        ]);

        $data = $request->all();

        $customerId = $data['customer'];
        $customer = Customer::query()->firstOrFail(['id']);

        $userId = $data['user'];
        $products = $data['order'];
        $title = $data['title'];

        if ($customer) {
            $orderTotal = 0;

            foreach ($products as $key => $val) {
                $price = Product::query()->findOrFail($val['id'], ['price'])->first()->price;

                $products[$key]['price'] = $price;

                $orderTotal += $price * $val['qnt'];
            }

            $list = ListModel::query()->create(['customer_id' => $customerId, 'user_id' => $userId, 'total' => $orderTotal, 'title' => $title]);

            foreach ($products as $key => $val) {
                ListProduct::query()->create([
                    'list_id' => $list->id,
                    'product_id' => $val['id'],
                    'quantity' => $val['qnt'],
                    'unit_price' => $val['price']
                ]);
            }

            return response(null, 201);
        }
    }
}
