<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'lacation_id',
        'total_price',
        'date of delivery',
        'status',

    ];

    public function user()
    {
        return $this->hasMany(User::class, 'user_id');
    }

    public function location()
    {
        return $this->belongsTo(Locations::class, 'location_id');
    }
    public function items()
    {
        return $this->hasMany(OrderItems::class);
    }
}
