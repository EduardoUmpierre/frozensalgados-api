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
        $id = $request->input('id');

        if ($id) {
            return Customer::query()->select(['id', 'name'])->where('name', 'LIKE', "%$id%")->orWhere('id', '=', $id)->get();
        }

        return Customer::all();
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

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        $id = $request->get('id');

        return response()->json(Customer::updateOrCreate(['id' => $id], $request->all()), 200);
    }
}
