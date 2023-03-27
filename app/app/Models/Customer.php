<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'customers';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'birthdate',
        'address',
        'neighborhood',
        'city',
        'zip_code',
        'complement',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id', 'id');
    }
}
