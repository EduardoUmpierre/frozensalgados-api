<?php

namespace App\Http\Controllers;

use App\Order;
use App\Customer;
use App\OrderProduct;
use App\Product;
use App\Repositories\OrderRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController
{
    private $orderRepository;

    /**
     * OrderController constructor.
     * @param OrderRepository $or
     */
    public function __construct(OrderRepository $or)
    {
        $this->orderRepository = $or;
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function getAll(Request $request): Collection
    {
        return $this->orderRepository->findAll($request->input('order'), $request->user()->id);
    }

    /**
     * @param int $id
     * @return Model
     */
    public function getOne(int $id): Model
    {
        return $this->orderRepository->findOneByid($id);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        return response()->json($this->orderRepository->create($request->all(), $request->user()->id), Response::HTTP_CREATED);
    }
}
