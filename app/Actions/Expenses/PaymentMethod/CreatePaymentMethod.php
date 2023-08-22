<?php

namespace App\Actions\Expenses\PaymentMethod;

use App\Models\PaymentMethod;

class CreatePaymentMethod
{
    public function create(array $data)
    {
        // Here you can add additional logic if needed
        return PaymentMethod::create($data);
    }
}
