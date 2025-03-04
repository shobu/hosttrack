<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'domain_name',
        'first_name',
        'last_name',
        'afm',
        'email',
        'hosting_cost',
        'hosting_start_date',
        'hosting_expiration_date',
        'notes',
    ];

    protected $casts = [
        'hosting_start_date' => 'date',
        'hosting_expiration_date' => 'date',
    ];
}
