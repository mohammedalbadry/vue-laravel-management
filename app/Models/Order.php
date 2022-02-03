<?php

namespace App\Models;

use App\Models\User;
use App\Models\Clint;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $fillable = [
        'total', 'discount','status', 'code',
        'note', 'clint_id', 'employee_id', 
    ];
    
    public function clint()
    {
        return $this->belongsTo(Clint::class);
    }

    public function employee()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
