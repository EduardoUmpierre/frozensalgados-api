<?php

namespace App\Repositories;

use App\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CategoryRepository
{
    private $productRepository;
    private $orderProductRepository;

    /**
     * CategoryRepository constructor.
     * @param ProductRepository $pr
     * @param OrderProductRepository $opr
     */
    public function __construct(ProductRepository $pr, OrderProductRepository $opr)
    {
        $this->productRepository = $pr;
        $this->orderProductRepository = $opr;
    }

    /**
     * @return Collection
     */
    public function findAll(): Collection
    {
        return Category::query()->select(['id', 'name'])->get();
    }

    /**
     * @param int $user
     * @return Collection
     */
    public function findAllWithOrderJoin(int $user): Collection
    {
        return Category::query()
            ->select(['customers.id', 'customers.name', 'customers.address', 'customers.phone'])
            ->join('orders', 'orders.customer_id', '=', 'customers.id')
            ->where('orders.user_id', '=', $user)
            ->groupBy('customers.id')
            ->get();
    }

    /**
     * @param int $id
     * @param array $columns
     * @return Model
     */
    public function findOneById(int $id, array $columns = ['*']): Model
    {
        return Category::query()->findOrFail($id, $columns);
    }

    /**
     * @param int $id
     * @param int $user
     * @return Model
     */
    public function findOneWithListsById(int $id, int $user): Model
    {
        return Category::query()->with(['lists' => function ($query) use ($user) {
            $query->where('user_id', '=', $user);
        }])->findOrFail($id);
    }

    /**
     * @param array $params
     * @return null
     */
    public function create(array $params)
    {
        Category::query()->create($params);

        return null;
    }

    /**
     * @param array $params
     * @param int $id
     * @return null
     */
    public function update(array $params, int $id)
    {
        Category::query()->findOrFail($id)->update($params);

        return null;
    }

    /**
     * @param int $id
     * @return null
     * @throws \Exception
     */
    public function delete(int $id)
    {
        Category::query()->findOrFail($id)->delete();

        return null;
    }

    /**
     * @param int $id
     * @param array|null $period
     * @return array
     */
    public function findTotalById(int $id, array $period = null)
    {
        $category = $this->findOneById($id);
        $products = $this->productRepository->findAllByCategory($id);

        return $this->getProductsTotal($category, $products, $period);
    }

    /**
     * @param Model $category
     * @param Collection $products
     * @param array|null $period
     * @return array
     */
    private function getProductsTotal(Model $category, Collection $products, array $period = null)
    {
        $response = [];
        $sum = 0;

        foreach ($products as $key => $val) {
            $product = $this->orderProductRepository->findTotalByProductId($val->id, $period);

            $response['list'][] = $product;
            $sum += $product->total;
        }

        $response['id'] = $category->id;
        $response['name'] = $category->name;
        $response['total'] = $sum;

        return $response;
    }

    /**
     * @param array|null $period
     * @return array
     */
    public function findTotal(array $period = null)
    {
        $categories = $this->findAll();
        $response = [];
        $sum = 0;

        foreach ($categories as $key => $val) {
            $category = $this->findTotalByCategoryId($val->id, $period);

            $response['list'][] = $category;
            $sum += $category->total;
        }

        $response['total'] = $sum;

        usort($response['list'], function ($a, $b) {
            return $b['total'] <=> $a['total'];
        });

        return $response;
    }

    /**
     * @param int $id
     * @param array|null $period
     * @return Model|static
     */
    public function findTotalByCategoryId(int $id, array $period = null)
    {
        return $this->orderProductRepository->findTotalByCategoryId($id, $period);
    }
}