<?php

namespace App\Actions\Expenses\Finance;

use App\Models\PaymentMethod;
use App\Models\ExpenseCategory;

class EditFinance
{
    public function edit($finance)
    {
        $currentTeamId = auth()->user()->currentTeam->id;

        $paymentMethods = PaymentMethod::where('team_id', $currentTeamId)->orderBy('order_column', 'asc')->get();
        $expenseCategories = ExpenseCategory::where('team_id', $currentTeamId)->where('type', 'expense')->orderBy('order_column', 'asc')->get();
        $incomeCategories = ExpenseCategory::where('team_id', $currentTeamId)->where('type', 'income')->orderBy('order_column', 'asc')->get();

        return [
            'finance' => $finance,
            'paymentMethods' => $paymentMethods,
            'expenseCategories' => $expenseCategories,
            'incomeCategories' => $incomeCategories,
        ];
    }
}
