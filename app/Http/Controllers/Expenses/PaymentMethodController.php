<?php

namespace App\Http\Controllers\Expenses;

use App\Http\Controllers\Controller;
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
        // バリデーション、データの保存など
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
