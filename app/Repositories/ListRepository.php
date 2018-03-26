<?php

namespace App\Repositories;

use App\ListModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Null_;

class ListRepository
{
    private $customerRepository;
    private $listProductRepository;
    private $productRepository;

    /**
     * ListRepository constructor.
     */
    public function __construct()
    {
        $this->customerRepository = app(CustomerRepository::class);
        $this->listProductRepository = app(ListProductRepository::class);
        $this->productRepository = app(ProductRepository::class);
    }

    /**
     * @param int $customer
     * @param int $user
     * @return Collection
     */
    public function findAllByCustomerId(int $customer, int $user): Collection
    {
        return ListModel::query()
            ->select('id', 'title')
            ->with(['listProduct'])
            ->where(['customer_id' => $customer, 'user_id' => $user])
            ->orderBy('created_at')
            ->get();
    }

    /**
     * @param int $customer
     * @param int $user
     * @return Model
     */
    public function findOneById(int $customer, int $user): Model
    {
        return ListModel::query()
            ->select('id', 'title')
            ->with(['listProduct', 'listProduct.product'])
            ->where(['id' => $customer, 'user_id' => $user])
            ->firstOrFail();
    }

    /**
     * @param array $params
     * @param int $user
     * @return null
     */
    public function create(array $params, int $user)
    {
        $customer = $this->customerRepository->findOneById($params['customer'], ['id']);
        $order = $params['order'];
        $orderTotal = 0;

        foreach ($order as $key => $val) {
            $price = $this->productRepository->findOneById($val['id'], ['price'])->first()->price;
            $order[$key]['price'] = $price;
            $orderTotal += $price * $val['qnt'];
        }

        $list = ListModel::query()->create([
            'title' => $params['title'],
            'total' => $orderTotal,
            'user_id' => $user,
            'customer_id' => $customer->id
        ]);

        foreach ($order as $key => $val) {
            $this->listProductRepository->create($list->id, $val['id'], $val['qnt'], $val['price']);
        }

        return null;
    }
}