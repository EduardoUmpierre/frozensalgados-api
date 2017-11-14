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
        $name = $request->input('name');

        if ($name) {
            return Product::query()->select(['id', 'name', 'price'])->where('name', 'LIKE', "%$name%")->get();
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
