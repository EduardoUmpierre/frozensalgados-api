<?php

namespace App\Repositories;

use App\Customer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CustomerRepository
{
    /**
     * @param int $id
     * @param int $role
     * @return Collection
     */
    public function findAll(int $id, int $role): Collection
    {
        $query = Customer::query()->select(['id', 'name', 'address', 'address_number', 'phone']);

        if ($role !== 1) {
            $query->where('user_id', '=', $id);
        }

        return $query->orderBy('id', 'DESC')->get();
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
     * @throws \Exception
     */
    public function delete(int $id)
    {
        Customer::query()->findOrFail($id)->delete();

        return null;
    }

    /**
     * @param int $id
     * @param int $role
     * @param array $period
     * @return array
     */
    public function findReport(int $id, int $role, array $period = null)
    {
        $customers = $this->findAll($id, $role);
        $response = [];
        $sum = 0;

        foreach ($customers as $key => $val) {
            $customer = $this->findTotalByCustomerId($val->id, $period);

            $response['list'][] = $customer;
            $sum += $customer->total;
        }

        $response['total'] = $sum;

        usort($response['list'], function ($a, $b) {
            return $b['total'] <=> $a['total'];
        });

        return $response;
    }

    /**
     * @param int $id
     * @param array|null $period
     * @return Model|static
     */
    public function findReportById(int $id, array $period = null)
    {
        $response = $this->findTotalByCustomerId($id, $period);
        $response['list'] = $this->findReportByCustomerId($id, $period);

        return $response;
    }

    /**
     * @param int $id
     * @param array|null $period
     * @return Model|static
     */
    public function findTotalByCustomerId(int $id, array $period = null)
    {
        $query = Customer::query()
            ->from('customers as c')
            ->select(['c.id', 'c.name', DB::raw('COALESCE(COUNT(o.id), 0) as quantity'), DB::raw('COALESCE(SUM(o.total), 0) as total')])
            ->join('orders as o', 'o.customer_id', '=', 'c.id')
            ->where('o.customer_id', '=', $id);

        if ($period && $period[0] && $period[1]) {
            $query->where(DB::raw('DATE(o.created_at)'), '>=', $period[0])
                ->where(DB::raw('DATE(o.created_at)'), '<=', $period[1]);
        }

        return $query->firstOrFail();
    }

    /**
     * @param int $id
     * @param array|null $period
     * @return \Illuminate\Support\Collection
     */
    public function findReportByCustomerId(int $id, array $period = null)
    {
        $query = \DB::table('orders as o')
            ->select('o.total', 'o.created_at as created_at', 'c.name as name')
            ->join('customers as c', 'c.id', '=', 'o.customer_id')
            ->where('o.customer_id', '=', $id);

        if ($period && $period[0] && $period[1]) {
            $query->where(DB::raw('DATE(o.created_at)'), '>=', $period[0])
                ->where(DB::raw('DATE(o.created_at)'), '<=', $period[1]);
        }

        $query->orderBy('o.created_at', 'DESC');

        return $query->get();
    }
}
