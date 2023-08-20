<?php

namespace App\Http\Requests\Expenses;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExpenseCategoryUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $teamId = auth()->user()->currentTeam->id;
        $categoryId = $this->route('category');  // 編集中のカテゴリIDを取得

        return [
            'name' => [
                'required',
                'string',
                'max:20',
                Rule::unique('expense_categories')->where(function ($query) use ($teamId) {
                    return $query->where('team_id', $teamId)
                        ->where('type', $this->type);
                })->ignore($categoryId, 'id'),  // 編集中のカテゴリIDを無視
            ],
            'description' => 'nullable|string|max:99',
        ];
    }
}
