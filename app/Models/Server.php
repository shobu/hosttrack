<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'ip_address',
        'cpu',
        'memory_gb',
        'disk_gb',
        'type',
        'hosting_company',
        'monthly_cost',
        'notes',
    ];

    // Σχέση: Ένας server έχει πολλούς πελάτες
    public function clients()
    {
        return $this->hasMany(Client::class);
    }
}