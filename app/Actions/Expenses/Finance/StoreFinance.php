<?php

namespace App\Actions\Expenses\Finance;

use App\Models\Expense;
use App\Models\PaymentMethod;
use Carbon\Carbon;

class StoreFinance
{
    public function run(array $data): Expense
    {
        $financeData = [
            'team_id' => $data['team_id'],
            'user_id' => $data['user_id'],
            'expense_category_id' => $data['category'],
            'amount' => $data['amount'],
            'description' => $data['description'] ?? null,
            'date' => $data['date'],
        ];

        if ($data['transaction_type'] === 'expense') {
            $financeData['payment_method_id'] = $data['payment_method'];
            $paymentMethod = PaymentMethod::find($data['payment_method']);
            $inputDate = Carbon::parse($data['date']);

            if (is_null($paymentMethod->closing_date)) {
                $financeData['reflected_date'] = $inputDate;
            } else {
                if ($inputDate->day <= $paymentMethod->closing_date) {
                    $reflectedDate = $inputDate->copy()->startOfMonth()->addMonths($paymentMethod->month_offset);
                } else {
                    $reflectedDate = $inputDate->copy()->startOfMonth()->addMonths($paymentMethod->month_offset + 1);
                }

                if ($paymentMethod->payment_date > $reflectedDate->daysInMonth) {
                    $reflectedDate->endOfMonth();
                } else {
                    $reflectedDate->day($paymentMethod->payment_date);
                }

                $financeData['reflected_date'] = $reflectedDate->startOfDay();
            }
        } elseif ($data['transaction_type'] === 'income') {
            $financeData['reflected_date'] = Carbon::parse($data['date']);
        }

        return Expense::create($financeData);
    }
}
