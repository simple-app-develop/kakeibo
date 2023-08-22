<?php

namespace App\Actions\Expenses\PaymentMethod;

use App\Models\PaymentMethod;

class GetPaymentMethods
{
    public function getByTeam($teamId)
    {
        return PaymentMethod::where('team_id', $teamId)
            ->orderBy('order_column', 'asc')
            ->get();
    }
}
