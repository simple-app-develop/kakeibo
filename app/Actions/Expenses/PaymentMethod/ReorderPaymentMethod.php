<?php

namespace App\Actions\Expenses\PaymentMethod;

use App\Models\PaymentMethod;

class ReorderPaymentMethod
{
    public function reorder(array $order)
    {
        foreach ($order as $index => $id) {
            $method = PaymentMethod::findOrFail($id);
            $method->order_column = $index;
            $method->save();
        }
    }
}
