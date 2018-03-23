<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id', 'total', 'status', 'user_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at', 'customer_id'
    ];

    /**
     * Get the customer record associated with the order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function customer()
    {
        return $this->hasOne('App\Customer', 'id', 'customer_id');
    }

    /**
     * Get the customer record associated with the order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    /**
     * Get the order's product list associated with the order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderProduct()
    {
        return $this->hasMany('App\OrderProduct', 'order_id');
    }
}