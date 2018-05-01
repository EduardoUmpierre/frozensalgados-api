<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportRepository
{
    private $orderRepository;
    private $categoryRepository;
    private $productRepository;
    private $customerRepository;
    private $userRepository;

    /**
     * ReportRepository constructor.
     * @param OrderRepository $or
     * @param CategoryRepository $cr
     * @param ProductRepository $pr
     * @param CustomerRepository $cur
     * @param UserRepository $ur
     */
    public function __construct(OrderRepository $or, CategoryRepository $cr, ProductRepository $pr,
                                CustomerRepository $cur, UserRepository $ur)
    {
        $this->customerRepository = $cur;
        $this->categoryRepository = $cr;
        $this->productRepository = $pr;
        $this->orderRepository = $or;
        $this->userRepository = $ur;
    }

    /**
     * @param array $period
     * @return \Illuminate\Http\JsonResponse
     */
    public function findReport(array $period)
    {
        $orders = DB::table('orders AS o')
            ->select([DB::raw('COUNT(o.id) AS orders_quantity'), DB::raw('COALESCE(SUM(o.total), 0) AS orders_total')])
            ->where(DB::raw('DATE(o.created_at)'), '>=', $period[0])
            ->where(DB::raw('DATE(o.created_at)'), '<=', $period[1])
            ->first();

        $customers = DB::table('customers AS c')
            ->select([DB::raw('COUNT(c.id) AS customers_quantity')])
            ->join('orders as o', 'o.customer_id', '=', 'c.id')
            ->where(DB::raw('DATE(o.created_at)'), '>=', $period[0])
            ->where(DB::raw('DATE(o.created_at)'), '<=', $period[1])
            ->first();

        $customers->average_ticket = $customers->customers_quantity > 0 ? $orders->orders_total / $customers->customers_quantity : 0;

        $products = DB::table('orders_products AS op')
            ->select([DB::raw('SUM(op.quantity) as quantity'), DB::raw('COALESCE(SUM(op.unit_price * op.quantity), 0) AS total'), 'p.name as name'])
            ->join('products AS p', 'p.id', '=', 'op.product_id')
            ->where(DB::raw('DATE(op.created_at)'), '>=', $period[0])
            ->where(DB::raw('DATE(op.created_at)'), '<=', $period[1])
            ->orderBy('total', 'DESC')
            ->limit(3)
            ->groupBy(['product_id'])
            ->get();

        $categories = DB::table('orders_products AS op')
            ->select([DB::raw('SUM(op.quantity) as quantity'), DB::raw('COALESCE(SUM(op.unit_price * op.quantity), 0) AS total'), 'c.name as name'])
            ->join('products AS p', 'p.id', '=', 'op.product_id')
            ->join('categories AS c', 'c.id', '=', 'p.category_id')
            ->where(DB::raw('DATE(op.created_at)'), '>=', $period[0])
            ->where(DB::raw('DATE(op.created_at)'), '<=', $period[1])
            ->orderBy('total', 'DESC')
            ->limit(3)
            ->groupBy(['category_id'])
            ->get();

        $sellers = DB::table('orders AS o')
            ->select([DB::raw('SUM(o.total) as total'), DB::raw('COUNT(o.id) AS quantity'), 'u.name as name'])
            ->join('users AS u', 'u.id', '=', 'o.user_id')
            ->where(DB::raw('DATE(o.created_at)'), '>=', $period[0])
            ->where(DB::raw('DATE(o.created_at)'), '<=', $period[1])
            ->orderBy('total', 'DESC')
            ->limit(3)
            ->groupBy(['user_id'])
            ->get();

        $response = array_merge((array)$orders, (array)$customers);
        $response = array_merge($response, (array)['products_list' => $products]);
        $response = array_merge($response, (array)['categories_list' => $categories]);
        $response = array_merge($response, (array)['sellers_list' => $sellers]);

        return response()->json($response);
    }

    /**
     * @param array|null $period
     * @return \Illuminate\Http\JsonResponse
     */
    public function findOrderReport(array $period = null)
    {
        $orders = \DB::table('orders AS o')
            ->select([DB::raw('COALESCE(SUM(o.total), 0) AS total'), DB::raw('COUNT(o.id) AS quantity')]);

        if ($period) {
            $orders->where(DB::raw('DATE(o.created_at)'), '>=', $period[0])
                ->where(DB::raw('DATE(o.created_at)'), '<=', $period[1]);
        }

        $response = (array)$orders->first();

        $orders = \DB::table('orders AS o')
            ->select(['o.id', 'o.total', 'o.created_at', 'c.name'])
            ->join('customers AS c', 'c.id', '=', 'o.customer_id');

        if ($period) {
            $orders->where(DB::raw('DATE(o.created_at)'), '>=', $period[0])
                ->where(DB::raw('DATE(o.created_at)'), '<=', $period[1]);
        }

        $orders->orderBy('created_at', 'DESC');

        $response['list'] = $orders->get();

        return response()->json($response);
    }

    /**
     * @param int $id
     * @param array|null $period
     * @return \Illuminate\Http\JsonResponse
     */
    public function findOrderReportById(int $id, array $period = null)
    {
        $response = (array)\DB::table('orders AS o')
            ->select(['o.total', 'o.created_at', 'c.name as name', 'u.name as seller'])
            ->join('customers AS c', 'c.id', '=', 'o.customer_id')
            ->join('users AS u', 'u.id', '=', 'o.user_id')
            ->where('o.id', '=', $id)
            ->first();

        $response['list'] = \DB::table('orders_products AS op')
            ->select([DB::raw('op.quantity * op.unit_price AS total'), 'op.quantity', 'p.name'])
            ->join('products AS p', 'p.id', '=', 'op.product_id')
            ->where('op.order_id', '=', $id)
            ->orderBy('total', 'DESC')
            ->get();

        return response()->json($response);
    }
}