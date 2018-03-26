<?php

namespace app\Services;

use App\Product;
use App\Repositories\ProductRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

class ProductService
{
    private $productRepository;

    /**
     * ProductService constructor.
     */
    public function __construct()
    {
        $this->productRepository = app(ProductRepository::class);
    }

    /**
     * @param string|null $search
     * @return Collection
     */
    public function getAll(string $search = null): Collection
    {
        if ($search) {
            return $this->productRepository->findAllBySearch($search);
        }

        return $this->productRepository->findAll();
    }

    /**
     * @param int $id
     * @return Collection|\Illuminate\Database\Eloquent\Model
     */
    public function getOne(int $id): Model
    {
        return $this->productRepository->findOneById($id);
    }

    /**
     * @param array $params
     * @return JsonResponse
     */
    public function create(array $params): JsonResponse
    {
        return response()->json($this->productRepository->create($params), 201);
    }

    /**
     * @param array $params
     * @param int $id
     * @return JsonResponse
     */
    public function update(array $params, int $id): JsonResponse
    {
        return response()->json($this->productRepository->update($params, $id), 200);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        return response()->json($this->productRepository->delete($id), 204);
    }
}