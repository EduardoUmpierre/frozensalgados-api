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
            ->select(['customers.id', 'customers.name', 'customers.address', 'customers.phone'])
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
            'name' => 'required',
            'cnpj' => 'required|unique:customers',
            'cep' => 'required',
            'address' => 'required',
            'address_number' => 'required',
            'city' => 'required',
            'district' => 'required'
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
        if (!$id) {
            return response()->json(null, 405);
        }

        $this->validate($request, [
            'name' => 'required',
            'cnpj' => 'required|unique:customers',
            'cep' => 'required',
            'address' => 'required',
            'address_number' => 'required',
            'city' => 'required',
            'district' => 'required'
        ]);

        $customer = Customer::query()->findOrFail($id);
        $customer->update($request->all());
        $customer->save();

        return response()->json(null, 200);
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

        $user = Customer::query()->findOrFail($id);
        $user->delete();

        return response()->json(null, 204);
    }
}
