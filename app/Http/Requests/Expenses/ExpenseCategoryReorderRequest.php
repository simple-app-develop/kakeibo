<?php

namespace App\Http\Requests\Expenses;

use App\Models\ExpenseCategory;
use Illuminate\Foundation\Http\FormRequest;

class ExpenseCategoryReorderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $teamId = auth()->user()->currentTeam->id;

        // $this->input('order') は、カテゴリのIDの配列と仮定しています。
        // このIDは、ログインしているユーザーの現在のチームに関連している必要があります。
        foreach ($this->input('order', []) as $categoryId) {
            $category = ExpenseCategory::find($categoryId);
            if (!$category || $category->team_id !== $teamId) {
                return false; // チームが正しくない場合、リクエストは拒否されます。
            }
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'order' => ['required', 'array'],
            'order.*' => ['exists:expense_categories,id'], // それぞれのIDがexpense_categoriesテーブルに存在することを確認します。
        ];
    }
}
