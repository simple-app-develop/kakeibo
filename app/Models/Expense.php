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
        'reflected_date',
        'type',
        'wallet_id',
        'target_wallet_id',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function expense_category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    // 新しく追加されたwalletリレーション
    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id');
    }
}
