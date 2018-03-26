<?php

namespace App\Repositories;

use App\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ProductRepository
{
    /**
     * @return Collection
     */
    public function findAll(): Collection
    {
        return Product::all();
    }

    /**
     * @param string $search
     * @return Collection
     */
    public function findAllBySearch(string $search): Collection
    {
        return Product::query()
            ->select(['id', 'name', 'price'])
            ->where('name', 'LIKE', "%$search%")
            ->orWhere('id', '=', $search)->get();
    }

    /**
     * @param int $id
     * @return Model
     */
    public function findOneById(int $id): Model
    {
        return Product::query()->findOrFail($id);
    }

    /**
     * @param array $params
     */
    public function create(array $params)
    {
        Product::query()->create($params);

        return;
    }

    /**
     * @param array $params
     * @param int $id
     */
    public function update(array $params, int $id)
    {
        Product::query()->findOrFail($id)->update($params);

        return;
    }

    public function delete(int $id)
    {
        Product::query()->findOrFail($id)->delete();

        return;
    }
}