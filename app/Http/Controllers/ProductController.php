<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll(Request $request)
    {
        $id = $request->input('id');

        if ($id) {
            return Product::query()->select(['id', 'name', 'price'])->where('name', 'LIKE', "%$id%")->orWhere('id', '=', $id)->get();
        }

        return Product::all();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'price' => 'required|numeric'
        ]);

        return response()->json(Product::create($request->all()), 201);
    }
}
