<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'user_id',
        'expense_category_id',
        'payment_method_id',
        'amount',
        'description',
        'date',
        'reflected_date'
    ];
}
