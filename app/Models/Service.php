<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['name', 'description', 'price', 'unit', 'pricing_model', 'package_qty', 'washer_wage', 'ironer_wage'];
}
