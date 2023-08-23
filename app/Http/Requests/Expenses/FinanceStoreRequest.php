<?php

namespace App\Http\Requests\Expenses;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FinanceStoreRequest extends FormRequest
{
    public function authorize()
    {
        // ここで、必要な場合は認可ロジックを追加できます。
        return true;
    }

    public function rules()
    {
        $teamId = auth()->user()->currentTeam->id;

        return [
            'transaction_type' => 'required|in:expense,income',
            'payment_method' => [
                'required_if:transaction_type,expense',
                Rule::exists('payment_methods', 'id')->where(function ($query) use ($teamId) {
                    $query->where('team_id', $teamId);
                }),
            ],
            'category' => [
                'nullable',
                Rule::exists('expense_categories', 'id')->where(function ($query) use ($teamId) {
                    $query->where('team_id', $teamId);
                }),
            ],
            'amount' => 'required|numeric|between:0,99999999',
            'description' => 'nullable|string|max:100',
            'date' => 'required|date',
        ];
    }
}
