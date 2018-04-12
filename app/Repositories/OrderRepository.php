<?php

namespace App\Repositories;

use App\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class OrderRepository
{
    private $customerRepository;
    private $orderProductRepository;
    private $productRepository;

    public function __construct()
    {
        $this->customerRepository = app(CustomerRepository::class);
        $this->orderProductRepository = app(OrderProductRepository::class);
        $this->productRepository = app(ProductRepository::class);
    }

    /**
     * @param string $order
     * @param int $user
     * @return Collection
     */
    public function findAll(string $order = null, int $user): Collection
    {
        return Order::with(['customer:id,name'])
            ->orderBy('created_at', $order ? $order : 'DESC')
            ->where('orders.user_id', '=', $user)
            ->get();
    }

    /**
     * @param int $customer
     * @param int $user
     * @return Collection
     */
    public function findAllByCustomerId(int $customer, int $user): Collection
    {
        return Order::query()
            ->select('id', 'created_at')
            ->where(['customer_id' => $customer, 'user_id' => $user])
            ->orderBy('created_at')
            ->get();
    }

    /**
     * @param int $id
     * @return Model
     */
    public function findOneById(int $id): Model
    {
        return Order::with(['customer:id,name,phone,address', 'orderProduct', 'orderProduct.product'])->findOrFail($id);
    }

    /**
     * @param int $id
     * @param int $user
     * @return Model
     */
    public function findOneByCustomerId(int $id, int $user): Model
    {
        return Order::query()
            ->select('id')
//            ->join('orders_products', 'orders_products.order_id', '=', 'orders.id')
            ->with(['orderProduct', 'orderProduct.product'])
            ->where(['orders.id' => $id, 'user_id' => $user])
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
        $products = $params['order'];
        $orderTotal = 0;

        foreach ($products as $key => $val) {
            $price = $this->productRepository->findOneById($val['id'], ['price'])->first()->price;
            $products[$key]['price'] = $price;
            $orderTotal += $price * $val['qnt'];
        }

        $order = Order::query()->create([
            'customer_id' => $customer->id,
            'total' => $orderTotal,
            'user_id' => $user,
            'status' => 0
        ]);

        foreach ($products as $key => $val) {
            $this->orderProductRepository->create($order->id, $val['id'], $val['qnt'], $val['price']);
        }

        return null;
    }
}