<?php

namespace App\Http\Controllers;

use App\Customer;
use App\ListModel;
use App\ListProduct;
use App\Product;
use App\Repositories\CustomerRepository;
use App\Repositories\ListRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Passport\Passport;

class ListController extends Controller
{
    private $listRepository;

    /**
     * ListController constructor.
     * @param ListRepository $lr
     */
    public function __construct(ListRepository $lr)
    {
        $this->listRepository = $lr;
    }

    /**
     * @param Request $request
     * @return Collection|\Illuminate\Http\JsonResponse
     */
    public function getAll(Request $request): JsonResponse
    {
        $customer = $request->input('customer');

        if (!$customer) {
            return response()->json(null, Response::HTTP_METHOD_NOT_ALLOWED);
        }

        return response()->json($this->listRepository->findAllByCustomerId($customer, $request->user()->id));
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function getOne(Request $request, $id): JsonResponse
    {
        return response()->json($this->listRepository->findOneById($id, $request->user()->id));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'customer' => 'required',
            'order' => 'required'
        ]);

        return response()->json($this->listRepository->create($request->all(), $request->user()->id), Response::HTTP_CREATED);
    }
}
