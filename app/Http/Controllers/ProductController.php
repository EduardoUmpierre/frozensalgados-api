<?php

namespace App\Http\Controllers;

use App\Repositories\ProductRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    private $productRepository;

    /**
     * ProductController constructor.
     * @param ProductRepository $pr
     */
    public function __construct(ProductRepository $pr)
    {
        $this->productRepository = $pr;
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function getAll(Request $request): Collection
    {
        $search = $request->input('id');

        if ($search) {
            return $this->productRepository->findAllBySearch($search);
        }

        return $this->productRepository->findAll();
    }

    /**
     * @param int $id
     * @return Model
     */
    public function getOne(int $id): Model
    {
        return $this->productRepository->findOneById($id);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'weight' => 'required|regex:/^[0-9](\.?[0-9]+)*(\,[0-9]+)$/',
            'price' => 'required|regex:/^[0-9](\.?[0-9]+)*(\,[0-9]+)$/'
        ]);

        return response()->json($this->productRepository->create($request->all()), Response::HTTP_CREATED);
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
            'weight' => 'required|regex:/^[0-9](\.?[0-9]+)*(\,[0-9]+)$/',
            'price' => 'required|regex:/^[0-9](\.?[0-9]+)*(\,[0-9]+)$/'
        ]);

        return response()->json($this->productRepository->update($request->all(), $id));
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        return response()->json($this->productRepository->delete($id), Response::HTTP_NO_CONTENT);
    }
}
