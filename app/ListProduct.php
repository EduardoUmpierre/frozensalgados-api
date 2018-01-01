<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ListProduct extends Model
{
    protected $table = 'lists_products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'list_id', 'product_id', 'quantity', 'unit_price'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at'
    ];

    /**
     * Get the product record associated with the order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function product()
    {
        return $this->hasOne('App\Product', 'id', 'product_id');
    }
}
