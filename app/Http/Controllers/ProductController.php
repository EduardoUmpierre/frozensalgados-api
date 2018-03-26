<?php

namespace App\Http\Controllers;

use App\Product;
use app\Services\ProductService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $productService;

    /**
     * ProductController constructor.
     * @param ProductService $ps
     */
    public function __construct(ProductService $ps)
    {
        $this->productService = $ps;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(Request $request): Collection
    {
        return $this->productService->getAll($request->input('id'));
    }

    /**
     * @param int $id
     * @return Model
     */
    public function getOne(int $id): Model
    {
        return $this->productService->getOne($id);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'price' => 'required|numeric'
        ]);

        return $this->productService->create($request->all());
    }

    /**
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'price' => 'required|numeric'
        ]);

        return $this->productService->update($request->all(), $id);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        return $this->productService->delete($id);
    }
}
