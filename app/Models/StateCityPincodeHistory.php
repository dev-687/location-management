<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StateCityPincodeHistory extends Model
{
    protected $table = 'state_city_pincode_history';
    protected $fillable = ['state_id', 'city_id', 'pincode', 'user_id', 'state_city_pincode_record_id'];
}
