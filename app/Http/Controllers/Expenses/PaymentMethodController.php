<?php

namespace App\Http\Controllers\Expenses;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

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
        // バリデーション
        $request->validate([
            'name' => 'required|string|max:255',
            'closing_date' => 'nullable|integer|min:1|max:31',
            'payment_date' => 'nullable|integer|min:1|max:31',
            'month_offset' => 'required|integer|min:0|max:3',
        ]);

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
