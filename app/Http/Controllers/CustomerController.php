<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll(Request $request)
    {
        $all = $request->input('all');

        if ($all) {
            return Customer::query()
                ->select(['id', 'name'])
                ->get();
        }

        return Customer::query()
            ->select('customers.*')
            ->join('orders', 'orders.customer_id', '=', 'customers.id')
            ->where('orders.user_id', '=', $request->user()->id)
            ->groupBy('customers.id')
            ->get();
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     *
     * @todo Remover listagem dos produtos
     */
    public function getOne(Request $request, $id)
    {
        $lists = $request->input('lists');

        if ($lists == '0') {
            $query = Customer::query()->findOrFail($id);
        } else {
            $query = Customer::query()->with(['lists' => function ($query) use ($request) {
                $query->where('user_id', '=', $request->user()->id);
            }])->findOrFail($id);
        }

        return $query;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        return response()->json(Customer::create($request->all()), 201);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        $id = $request->get('id');

        return response()->json(Customer::updateOrCreate(['id' => $id], $request->all()), 200);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete(Request $request, $id)
    {
        if (!$id) {
            return response()->json(null, 405);
        }

        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(null, 404);
        }

        $customer->delete();

        return response()->json(null, 200);
    }
}
