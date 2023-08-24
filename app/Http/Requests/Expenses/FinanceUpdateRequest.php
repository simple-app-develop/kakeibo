<?php

namespace App\Http\Requests\Expenses;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FinanceUpdateRequest extends FormRequest
{
    public function authorize()
    {
        // 必要な場合は、このエンティティの更新権限に関するロジックを追加できます。
        // 例: $this->route('finance')でExpenseモデルのインスタンスを取得し、ユーザーが更新できるかどうかを確認します。
        return true;
    }

    public function rules()
    {
        $teamId = auth()->user()->currentTeam->id;

        return [
            'transaction_type' => 'required|in:expense,income,transfer',
            'payment_method' => [
                'required_if:transaction_type,expense',
                Rule::exists('payment_methods', 'id')->where(function ($query) use ($teamId) {
                    $query->where('team_id', $teamId);
                }),
            ],
            'wallet_id' => [
                'required_if:transaction_type,transfer',
                Rule::exists('wallets', 'id')->where(function ($query) use ($teamId) {
                    $query->where('team_id', $teamId);
                }),
                'different:target_wallet_id',
            ],
            'target_wallet_id' => [
                'required_if:transaction_type,transfer',
                Rule::exists('wallets', 'id')->where(function ($query) use ($teamId) {
                    $query->where('team_id', $teamId);
                }),
                'different:wallet_id',
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
