<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;

class Clint extends Model
{
    protected $fillable = [
        'name', 'address', 'phone'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

}
