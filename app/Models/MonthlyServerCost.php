<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyServerCost extends Model
{
    protected $fillable = ['month', 'total_cost'];

    protected $casts = [
        'month' => 'date',
    ];
}