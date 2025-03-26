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
        'company',
        'hosting_cost',
        'hosting_start_date',
        'hosting_expiration_date',
        'notes',
        'status',
    ];

    protected $casts = [
        'hosting_start_date' => 'date',
        'hosting_expiration_date' => 'date',
    ];

    public function renewalLogs()
    {
        return $this->hasMany(RenewalLog::class);
    }  

    public function getCanRenewAttribute()
    {
        return \Carbon\Carbon::parse($this->hosting_expiration_date)->lte(now()->addMonth());
    }

    public function payments() {
        return $this->hasMany(PaymentLog::class);
    }

    public function paymentLogs()
    {
        return $this->hasMany(PaymentLog::class, 'client_id');
    }
    
}
