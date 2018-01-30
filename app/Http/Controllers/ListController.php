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
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll(Request $request)
    {
        $customer = $request->input('customer');

        return ListModel::query()->select('id', 'title')->with(['listProduct'])->where([
            'customer_id' => $customer,
            'user_id' => $request->user()->id
        ])->orderBy('created_at')->get();
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|static
     */
    public function getOne(Request $request, $id)
    {
        return ListModel::query()->select('id', 'title')->with(['listProduct', 'listProduct.product'])->where([
            'id' => $id,
            'user_id' => $request->user()->id
        ])->firstOrFail();
    }

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

        $userId = $request->user()->id;
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

            return response()->json(null, 201);
        }
    }
}
