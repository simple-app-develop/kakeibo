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
        $paymentMethods = PaymentMethod::where('team_id', auth()->user()->currentTeam->id)
            ->orderBy('order_column', 'asc')
            ->get();

        return view('expenses.payment_method.index', [
            'paymentMethods' => $paymentMethods,
            'isPermission' => true, // これは適切な認可ロジックに置き換えてください
        ]);
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
        return redirect()->route('payment-method.index')->with('success', 'Payment method registered successfully.');
    }


    public function edit($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);

        return view('expenses.payment_method.edit', compact('paymentMethod'));
    }


    public function update(Request $request, $id)
    {
        $method = PaymentMethod::find($id);

        // Validation rules...
        // ... (ここに更新時のバリデーションルールを記述)

        $data = $request->all();

        $method->update($data);

        return redirect()->route('payment-method.index')->with('success', 'Payment method updated successfully.');
    }

    public function destroy($id)
    {
        $method = PaymentMethod::find($id);
        $method->delete();

        return redirect()->route('payment-method.index')->with('success', 'Payment method deleted successfully.');
    }

    public function reorder(Request $request)
    {
        $order = $request->input('order');

        foreach ($order as $index => $id) {
            $method = PaymentMethod::find($id);
            $method->order_column = $index;
            $method->save();
        }

        return response()->json(['message' => 'Order updated successfully']);
    }
}
