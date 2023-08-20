<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'type',
        'name',
        'description',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
