<?php

namespace App\Repositories;

use App\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderRepository
{
    private $customerRepository;
    private $orderProductRepository;
    private $productRepository;

    /**
     * OrderRepository constructor.
     */
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
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    /**
     * @param int $id
     * @return Model
     */
    public function findOneById(int $id): Model
    {
        return Order::with(['customer:id,name,phone,address,cnpj', 'orderProduct', 'orderProduct.product'])->findOrFail($id);
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
            ->with(['orderProduct', 'orderProduct.product', 'orderProduct.product.category'])
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
        $customer = $this->customerRepository->findOneById($params['customer']['id'], ['id']);
        $products = $params['order'];
        $orderTotal = 0;

        foreach ($products as $key => $val) {
            $orderTotal += $val['price'] * $val['qnt'];
        }

        $orderParams = [
            'customer_id' => $customer->id,
            'total' => $orderTotal,
            'user_id' => $user,
            'status' => $params['status'],
            'payment_date' => $params['payment_date'],
            'payment_method' => $params['payment_method'],
            'delivery_date' => $params['delivery_date'],
            'installments' => $params['installments']
        ];

        if (isset($params['comments'])) {
            $orderParams['comments'] = $params['comments'];
        }

        $order = Order::query()->create($orderParams);

        foreach ($products as $key => $val) {
            $this->orderProductRepository->create($order->id, $val['id'], $val['qnt'], $val['price']);
        }

        return null;
    }

    /**
     * @param int $id
     * @param array|null $period
     * @return Model|static
     */
    public function totalByUserId(int $id, array $period = null)
    {
        $query = Order::query()
            ->from('orders as o')
            ->select(['u.id', 'u.name', DB::raw('COALESCE(COUNT(o.id), 0) as quantity'), DB::raw('COALESCE(SUM(o.total), 0) as total')])
            ->join('users as u', 'u.id', '=', 'o.user_id')
            ->where('o.user_id', '=', $id);

        if ($period && $period[0] && $period[1]) {
            $query->where(DB::raw('DATE(o.created_at)'), '>=', $period[0])
                ->where(DB::raw('DATE(o.created_at)'), '<=', $period[1]);
        }

        return $query->firstOrFail();
    }

    /**
     * @param int $id
     * @param array|null $period
     * @return Collection|static[]
     */
    public function findAllByUserId(int $id, array $period = null)
    {
        $query = Order::query()
            ->from('orders as o')
            ->select('o.total', 'o.created_at', 'c.name as name')
            ->join('customers as c', 'c.id', '=', 'o.customer_id')
            ->where('o.user_id', '=', $id);

        if ($period && $period[0] && $period[1]) {
            $query->where(DB::raw('DATE(o.created_at)'), '>=', $period[0])
                ->where(DB::raw('DATE(o.created_at)'), '<=', $period[1]);
        }

        $query->orderBy('o.created_at', 'DESC');

        return $query->get();
    }

    /**
     * @param array $period
     * @return array
     */
    public function findReport(array $period = null)
    {
        $orders = Order::query()->get();
        $response = [];
        $sum = 0;

        foreach ($orders as $key => $val) {
            $order = $this->findTotalByOrderId($val->id, $period);

            $response['list'][] = $order;
            $sum += $order->total;
        }

        $response['total'] = $sum;

        usort($response['list'], function ($a, $b) {
            return $b['total'] <=> $a['total'];
        });

        return $response;
    }

    /**
     * @param int $id
     * @return Collection|Model
     */
    public function findOnePdfDataById(int $id)
    {
        return Order::with(['customer', 'orderProduct', 'orderProduct.product'])->findOrFail($id);
    }
}
