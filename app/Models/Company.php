<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Company extends Model
{
    use HasFactory, Uuid;

    protected $fillable = [
        'user_id',
        'country_id',
        'service_id',
        'name',
        'email',
        'phone_number',
        'description',
    ];

    public function country()
    {
    	return $this->belongsTo(Country::class,'country_id')->select(['id', 'name']);
    }

    public function service()
    {
    	return $this->belongsTo(Service::class, 'service_id')->select(['id', 'name']);
    }

    public function owner()
    {
    	return $this->belongsTo(User::class,'user_id')->select(['id', 'first_name', 'last_name']);
    }
}
