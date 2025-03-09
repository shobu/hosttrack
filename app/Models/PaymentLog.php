<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentLog extends Model {
    use HasFactory;

    protected $fillable = [
        'client_id',
        'amount',
        'payment_date',
        'invoice_number',
        'support_service',
        'support_cost', 
    ];

    public function client() {
        return $this->belongsTo(Client::class);
    }
}
