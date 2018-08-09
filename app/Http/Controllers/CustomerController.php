<?php

namespace App\Http\Controllers;

use App\Repositories\CustomerRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CustomerController extends Controller
{
    private $customerRepository;

    /**
     * CustomerController constructor.
     * @param $cr
     */
    public function __construct(CustomerRepository $cr)
    {
        $this->customerRepository = $cr;
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function getAll(Request $request): Collection
    {
        return $this->customerRepository->findAll($request->user()->id, $request->user()->role);
    }

    /**
     * @param int $id
     * @return Model
     */
    public function getOne(int $id): Model
    {
        return $this->customerRepository->findOneById($id);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $this->validate($request, [
            'user_id' => 'required',
            'name' => 'required',
            'cnpj' => 'required|unique:customers',
            'cep' => 'required',
            'address' => 'required',
            'address_number' => 'required',
            'city' => 'required',
            'district' => 'required'
        ]);

        return response()->json($this->customerRepository->create($request->all()), Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $this->validate($request, [
            'user_id' => 'required',
            'name' => 'required',
            'cnpj' => 'required|unique:customers,cnpj,' . $id,
            'cep' => 'required',
            'address' => 'required',
            'address_number' => 'required',
            'city' => 'required',
            'district' => 'required'
        ]);

        return response()->json($this->customerRepository->update($request->all(), $id));
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete(int $id): JsonResponse
    {
        return response()->json($this->customerRepository->delete($id), Response::HTTP_NO_CONTENT);
    }
}
