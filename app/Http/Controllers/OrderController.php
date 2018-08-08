<?php

namespace App\Http\Controllers;

use App\Repositories\OrderRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
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
     * @param Request $request
     * @param int $customer
     * @return Collection
     */
    public function getAllByCustomer(Request $request, int $customer): Collection
    {
        return $this->orderRepository->findAllByCustomerId($customer, $request->user()->id);
    }

    /**
     * @param int $id
     * @return Model
     */
    public function getOne(int $id): Model
    {
        return $this->orderRepository->findOneById($id);
    }

    /**
     * @param Request $request
     * @param int $customer
     * @return Model
     */
    public function getOneByCustomer(Request $request, int $customer): Model
    {
        return $this->orderRepository->findOneByCustomerId($customer, $request->user()->id);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'customer' => 'required',
            'order' => 'required',
            'status' => 'required|numeric|min:1|max:3',
            'payment_method' => 'required|numeric|min:1|max:4',
            'payment_date' => 'required|date',
            'delivery_date' => 'required|date',
            'installments' => 'required|numeric|min:1|max:3'
        ]);

        return response()->json($this->orderRepository->create($request->all(), $request->user()->id), Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @return int|mixed
     */
    public function markAsRead(Request $request) {
        return $this->orderRepository->markAsRead($request->get('id'));
    }
}
