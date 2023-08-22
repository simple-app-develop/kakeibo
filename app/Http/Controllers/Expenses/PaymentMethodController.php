<?php

namespace App\Http\Controllers\Expenses;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaymentMethodController extends Controller
{
    public function index()
    {
        // 支払い方法の一覧を取得して表示
    }

    public function create()
    {
        // 支払い方法の作成フォームを表示
        return view('expenses.payment_method.create');
    }

    public function store(Request $request)
    {
        // 基本のバリデーションルール
        $rules = [
            'name' => [
                'required',
                'string',
                'max:20',
                Rule::unique('payment_methods')->where(function ($query) use ($request) {
                    return $query->where('team_id', $request->user()->currentTeam->id);
                })
            ],
            'isCreditCard' => 'required|in:0,1'
        ];

        if ($request->input('isCreditCard') == 0) {
            // クレジットカードではない場合、フィールドの値をnullにセット

            $data['closing_date'] = null;
            $data['payment_date'] = null;
            $data['month_offset'] = null;
        } else {
            // クレジットカードの場合のバリデーションルールを追加

            $rules['closing_date'] = 'required|integer|min:1|max:31';
            $rules['payment_date'] = 'required|integer|min:1|max:31';
            $rules['month_offset'] = 'required|integer|min:0|max:3';
        }

        // バリデーションの実行
        $request->validate($rules);

        $data = $request->all();
        $data['team_id'] = auth()->user()->currentTeam->id;

        // データの保存
        PaymentMethod::create($data);

        // リダイレクト
        // return redirect()->route('payment-method.index')->with('success', 'Payment method registered successfully.');
    }


    public function edit($id)
    {
        // 指定されたIDの支払い方法を取得して編集フォームを表示
    }

    public function update(Request $request, $id)
    {
        // 指定されたIDの支払い方法を更新
    }

    public function destroy($id)
    {
        // 指定されたIDの支払い方法を削除
    }
}
