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
        return Product::with(['category'])->get();
    }

    /**
     * @param string $search
     * @return Collection
     */
    public function findAllBySearch(string $search): Collection
    {
        return Product::query()
            ->with(['category'])
            ->select(['id', 'name', 'price'])
            ->where('name', 'LIKE', "%$search%")
            ->orWhere('id', '=', $search)->get();
    }

    /**
     * @param int $id
     * @param array $columns
     * @return Model
     */
    public function findOneById(int $id, array $columns = ['id', 'name', 'price']): Model
    {
        return Product::query()->with('category')->findOrFail($id, $columns);
    }

    /**
     * @param array $params
     * @return null
     */
    public function create(array $params)
    {
        $params['price'] = $this->fixCurrencyFormat($params['price']);

        Product::query()->create($params);

        return null;
    }

    /**
     * @param array $params
     * @param int $id
     * @return null
     */
    public function update(array $params, int $id)
    {
        $params['price'] = $this->fixCurrencyFormat($params['price']);

        Product::query()->findOrFail($id)->update($params);

        return null;
    }

    /**
     * @param int $id
     * @return null
     * @throws \Exception
     */
    public function delete(int $id)
    {
        Product::query()->findOrFail($id)->delete();

        return null;
    }

    /**
     * @param float $value
     * @return mixed
     */
    private function fixCurrencyFormat($value)
    {
        $source = array('.', ',');
        $replace = array('', '.');

        return str_replace($source, $replace, $value);
    }
}