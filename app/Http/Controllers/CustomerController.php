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
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     *
     * @todo Adicionar usuário logado na consulta
     * @todo Remover listagem dos produtos
     */
    public function getOne($id)
    {
        return Customer::query()->with('lists')->whereHas('lists', function ($query) {
            $query->where('user_id', '=', 1);
        })->findOrFail($id);
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
}
