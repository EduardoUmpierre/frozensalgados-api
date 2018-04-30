<?php

namespace App\Repositories;

use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UserRepository
{
    private $orderRepository;

    /**
     * UserRepository constructor.
     * @param OrderRepository $or
     */
    public function __construct(OrderRepository $or)
    {
        $this->orderRepository = $or;
    }

    /**
     * @return Collection
     */
    public function findAll(): Collection
    {
        return User::query()->select(['id', 'name', 'email', 'role'])->get();
    }

    /**
     * @param int $id
     * @param array $columns
     * @return Model
     */
    public function findOneById(int $id, array $columns = ['*']): Model
    {
        return User::query()->findOrFail($id, $columns);
    }

    /**
     * @param array $params
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(array $params): JsonResponse
    {
        if ($params['password'] === $params['passwordRepeat']) {
            unset($params['passwordRepeat']);

            User::query()->create(array_merge($params, ['password' => app('hash')->make($params['password'])]));

            return response()->json(null, Response::HTTP_CREATED);
        }

        return response()->json(['error' => 'passwordNotEqual'], Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * @param array $params
     * @param int $id
     * @return JsonResponse
     */
    public function update(array $params, int $id): JsonResponse
    {
        $user = User::query()->findOrFail($id);

        if (isset($params['password']) || isset($params['passwordRepeat'])) {
            if (isset($params['password']) && isset($params['passwordRepeat']) && $params['password'] === $params['passwordRepeat']) {
                $params = array_merge($params, ['password' => app('hash')->make($params['password'])]);
            } else {
                return response()->json(['error' => 'passwordNotEqual'], Response::HTTP_METHOD_NOT_ALLOWED);
            }
        }

        $user->update($params);

        return response()->json(null);
    }

    /**
     * @param int $id
     * @return null
     * @throws \Exception
     */
    public function delete(int $id)
    {
        User::query()->findOrFail($id)->delete();

        return null;
    }

    /**
     * @param int $id
     * @param array|null $period
     * @return Model|static
     */
    public function findTotalById(int $id, array $period = null)
    {
        $response = $this->orderRepository->totalByUserId($id, $period);
        $response['list'] = $this->orderRepository->findAllByUserId($id, $period);

        return $response;
    }

    /**
     * @param array|null $period
     * @return array
     */
    public function findTotal(array $period = null)
    {
        $users = $this->findAll();
        $response = [];
        $sum = 0;

        foreach ($users as $key => $val) {
            $seller = $this->findTotalById($val->id, $period);
            $sum += $seller->total;

            $response['list'][] = $seller;
        }

        $response['total'] = $sum;

        return $response;
    }
}