<?php

namespace App\Actions\Expenses\PaymentMethod;

use App\Models\PaymentMethod;

class UpdatePaymentMethod
{
    public function update($id, array $data)
    {
        $method = PaymentMethod::findOrFail($id);
        $method->update($data);
    }
}
