<?php

namespace App\Repositories;

use App\Customer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CustomerRepository
{
    /**
     * @return Collection
     */
    public function findAll(): Collection
    {
        return Customer::query()->select(['id', 'name'])->get();
    }

    /**
     * @param int $user
     * @return Collection
     */
    public function findAllWithOrderJoin(int $user): Collection
    {
        return Customer::query()
            ->select(['customers.id', 'customers.name', 'customers.address', 'customers.phone'])
            ->join('orders', 'orders.customer_id', '=', 'customers.id')
            ->where('orders.user_id', '=', $user)
            ->groupBy('customers.id')
            ->get();
    }

    /**
     * @param int $id
     * @param array $columns
     * @return Model
     */
    public function findOneById(int $id, array $columns = ['*']): Model
    {
        return Customer::query()->findOrFail($id, $columns);
    }

    /**
     * @param int $id
     * @param int $user
     * @return Model
     */
    public function findOneWithListsById(int $id, int $user): Model
    {
        return Customer::query()->with(['lists' => function ($query) use ($user) {
            $query->where('user_id', '=', $user);
        }])->findOrFail($id);
    }

    /**
     * @param array $params
     * @return null
     */
    public function create(array $params)
    {
        Customer::query()->create($params);

        return null;
    }

    /**
     * @param array $params
     * @param int $id
     * @return null
     */
    public function update(array $params, int $id)
    {
        Customer::query()->findOrFail($id)->update($params);

        return null;
    }

    /**
     * @param int $id
     * @return null
     */
    public function delete(int $id)
    {
        Customer::query()->findOrFail($id)->delete();

        return null;
    }
}