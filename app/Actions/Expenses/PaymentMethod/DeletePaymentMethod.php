<?php

namespace App\Actions\Expenses\PaymentMethod;

use App\Models\PaymentMethod;

class DeletePaymentMethod
{
    public function delete($id)
    {
        $method = PaymentMethod::findOrFail($id);
        $method->delete();
    }
}
