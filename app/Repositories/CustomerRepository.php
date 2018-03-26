<?php

namespace App\Repositories;

use App\Customer;
use Illuminate\Database\Eloquent\Model;

class CustomerRepository
{
    /**
     * @param int $id
     * @param array $columns
     * @return Model
     */
    public function findOneById(int $id, array $columns = ['*']): Model
    {
        return Customer::query()->findOrFail($id, $columns);
    }
}