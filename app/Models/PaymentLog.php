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
    ];

    public function client() {
        return $this->belongsTo(Client::class);
    }
}
