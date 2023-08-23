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
        'month_offset'
    ];

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
