<?php

namespace App\Repositories;

use App\OrderProduct;
use Illuminate\Support\Facades\DB;

class OrderProductRepository
{
    private $productRepository;

    /**
     * OrderProductRepository constructor.
     * @param ProductRepository $pr
     */
    public function __construct(ProductRepository $pr)
    {
        $this->productRepository = $pr;
    }

    /**
     * @param int $order
     * @param int $product
     * @param int $quantity
     * @param float $price
     */
    public function create(int $order, int $product, int $quantity, float $price)
    {
        OrderProduct::query()->create([
            'order_id' => $order,
            'product_id' => $product,
            'quantity' => $quantity,
            'unit_price' => $price
        ]);
    }

    /**
     * @param int $id
     * @param array|null $period
     * @return \Illuminate\Database\Eloquent\Model|static
     */
    public function findTotalByProductId(int $id, array $period = null)
    {
        $query = OrderProduct::query()
            ->from('orders_products as op')
            ->select(['p.name', DB::raw('COALESCE(SUM(op.unit_price * op.quantity), 0) as total')])
            ->join('products as p', 'p.id', '=', 'op.product_id')
            ->where('op.product_id', '=', $id);

        if ($period) {
            $query->where(DB::raw('DATE(op.created_at)'), '>=', $period[0])
                ->where(DB::raw('DATE(op.created_at)'), '<=', $period[1]);
        }

        return $query->firstOrFail();
    }

    /**
     * @param array|null $period
     * @return array
     */
    public function findTotal(array $period = null)
    {
        $products = $this->productRepository->findAll();
        $response = [];

        foreach ($products as $key => $val) {
            $response[] = $this->findTotalByProductId($val->id, $period);
        }

        return $response;
    }

    /**
     * @param int $id
     * @param array|null $period
     * @return \Illuminate\Database\Eloquent\Model|static
     */
    public function findTotalByCategoryId(int $id, array $period = null)
    {
        $query = OrderProduct::query()
            ->from('orders_products as op')
            ->select(['c.name', DB::raw('COALESCE(SUM(op.unit_price * op.quantity), 0) as total')])
            ->join('products as p', 'p.id', '=', 'op.product_id')
            ->join('categories as c', 'c.id', '=', 'p.category_id')
            ->where('p.category_id', '=', $id);

        if ($period) {
            $query->where(DB::raw('DATE(op.created_at)'), '>=', $period[0])
                ->where(DB::raw('DATE(op.created_at)'), '<=', $period[1]);
        }

        return $query->firstOrFail();
    }
}