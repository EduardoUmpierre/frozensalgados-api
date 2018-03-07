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
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function getOne(Request $request, $id)
    {
        $query = Product::query()->findOrFail($id);

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
            'price' => 'required|numeric'
        ]);

        return response()->json(Product::create($request->all()), 201);
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

        return response()->json(Product::updateOrCreate(['id' => $id], $request->all()), 200);
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

        $product = Product::find($id);

        if (!$product) {
            return response()->json(null, 404);
        }

        $product->delete();

        return response()->json(null, 200);
    }
}
