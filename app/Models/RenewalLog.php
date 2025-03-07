<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RenewalLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'old_expiration_date',
        'new_expiration_date',
        'renewed_at',
        'payment_id' 
    ];


    public $timestamps = false;
    
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function payment()
    {
        return $this->belongsTo(PaymentLog::class, 'payment_id');
    }

}

