<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'name',
        'closing_date',
        'payment_date',
        'month_offset',
        "wallet_id"
    ];

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
