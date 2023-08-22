<?php

namespace App\Http\Controllers\Expenses;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\PaymentMethod;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FinanceController extends Controller
{
    public function index()
    {

        return view('expenses.finance.index');
    }

    public function create()
    {
        $currentTeamId = auth()->user()->currentTeam->id;

        $paymentMethods = PaymentMethod::where('team_id', $currentTeamId)->orderBy('order_column', 'asc')->get();
        $expenseCategories = ExpenseCategory::where('team_id', $currentTeamId)->where('type', 'expense')->orderBy('order_column', 'asc')->get();
        $incomeCategories = ExpenseCategory::where('team_id', $currentTeamId)->where('type', 'income')->orderBy('order_column', 'asc')->get();

        return view('expenses.finance.create', [
            'paymentMethods' => $paymentMethods,
            'expenseCategories' => $expenseCategories,
            'incomeCategories' => $incomeCategories,
        ]);
    }


    public function store(Request $request)
    {
        $teamId = auth()->user()->currentTeam->id;

        $validatedData = $request->validate([
            'transaction_type' => 'required|in:expense,income',
            'payment_method' => [
                'required_if:transaction_type,expense',
                Rule::exists('payment_methods', 'id')->where(function ($query) use ($teamId) {
                    $query->where('team_id', $teamId);
                }),
            ],
            'category' => [
                'required',
                Rule::exists('expense_categories', 'id')->where(function ($query) use ($teamId, $request) {
                    $query->where('team_id', $teamId)
                        ->where('type', $request->transaction_type);
                }),
            ],
            'amount' => 'required|numeric|between:0,99999999',
            'description' => 'nullable|string',
            'date' => 'required|date',
        ]);


        $financeData = [
            'team_id' => auth()->user()->currentTeam->id,
            'user_id' => auth()->id(),
            'expense_category_id' => $validatedData['category'],
            'amount' => $validatedData['amount'],
            'description' => $validatedData['description'] ?? null,
            'date' => $validatedData['date'],
        ];

        if ($validatedData['transaction_type'] === 'expense') {
            // 資金データの支払方法IDを設定
            $financeData['payment_method_id'] = $validatedData['payment_method'];

            // 支払方法の詳細をデータベースから取得
            $paymentMethod = PaymentMethod::find($validatedData['payment_method']);

            // 入力された日付をCarbonインスタンスに変換
            $inputDate = \Carbon\Carbon::parse($validatedData['date']);

            // 支払方法に締め日が設定されていない場合（現金の場合など）
            if (is_null($paymentMethod->closing_date)) {
                // 現金の場合は、反映日として入力日をそのまま使用
                $financeData['reflected_date'] = $inputDate;
            } else {
                // 入力日が締め日以前の場合
                if ($inputDate->day <= $paymentMethod->closing_date) {
                    // 月の初めに設定し、オフセット月を追加して反映日を計算
                    $reflectedDate = $inputDate->copy()->startOfMonth()->addMonths($paymentMethod->month_offset);
                } else {
                    // 入力日が締め日より後の場合
                    // 月の初めに設定し、オフセット月 + 1を追加して反映日を計算
                    $reflectedDate = $inputDate->copy()->startOfMonth()->addMonths($paymentMethod->month_offset + 1);
                }

                // 設定された支払日がその月の最大日数を超える場合、その月の最後の日を支払日として使用
                if ($paymentMethod->payment_date > $reflectedDate->daysInMonth) {
                    $reflectedDate->endOfMonth();
                } else {
                    // その月の指定された支払日に設定
                    $reflectedDate->day($paymentMethod->payment_date);
                }

                // 資金データの反映日を日の始まり（0時0分0秒）に設定
                $financeData['reflected_date'] = $reflectedDate->startOfDay();
            }
        } elseif ($validatedData['transaction_type'] === 'income') {
            // 収入の場合の反映日のロジックをここに書く
            $financeData['reflected_date'] = \Carbon\Carbon::parse($validatedData['date']);
        }

        /* NOTE:
            $inputDate->copy()  ：入力日付のコピーを作成します。
                                    これにより、元の入力日付の値は変更されずに新しい日付を操作できます。

            startOfMonth()      ：日付をその月の初日に設定します。
                                    例えば、2023-08-31を2023-08-01に変更します。
                                    これは、その後の月の加算で問題を回避するために行います。
                                    具体的には、8月31日に1か月を加算すると、9月31日となってしまいますが、
                                    9月31日は存在しないため、結果が10月1日となってしまいます。
                                    これを避けるために、まず月の初日に戻してから月を加算することで、
                                    正確な月の加算を行います。

            addMonths($paymentMethod->month_offset)：
                                　オフセットとして指定された月数を追加します。
                                    この例では、支払いが翌月である場合、$paymentMethod->month_offsetは
                                    1になるため、1か月が追加されます。
        */

        // dd($financeData['reflected_date']->toDateTimeString());

        // 保存処理
        $finance = Expense::create($financeData);

        return redirect()->route('finance.index')->with('success', 'success');
    }

    public function edit(Expense $finance)
    {
        $currentTeamId = auth()->user()->currentTeam->id;

        $paymentMethods = PaymentMethod::where('team_id', $currentTeamId)->orderBy('order_column', 'asc')->get();
        $expenseCategories = ExpenseCategory::where('team_id', $currentTeamId)->where('type', 'expense')->orderBy('order_column', 'asc')->get();
        $incomeCategories = ExpenseCategory::where('team_id', $currentTeamId)->where('type', 'income')->orderBy('order_column', 'asc')->get();

        return view('expenses.finance.edit', [
            'finance' => $finance,
            'paymentMethods' => $paymentMethods,
            'expenseCategories' => $expenseCategories,
            'incomeCategories' => $incomeCategories,
        ]);
    }


    public function update(Request $request, Expense $finance)
    {
        $teamId = auth()->user()->currentTeam->id;

        $validatedData = $request->validate([
            'transaction_type' => 'required|in:expense,income',
            'payment_method' => [
                'required_if:transaction_type,expense',
                Rule::exists('payment_methods', 'id')->where(function ($query) use ($teamId) {
                    $query->where('team_id', $teamId);
                }),
            ],
            'category' => [
                'required',
                Rule::exists('expense_categories', 'id')->where(function ($query) use ($teamId, $request) {
                    $query->where('team_id', $teamId)
                        ->where('type', $request->transaction_type);
                }),
            ],
            'amount' => 'required|numeric|between:0,99999999',
            'description' => 'nullable|string',
            'date' => 'required|date',
        ]);


        $financeData = [
            'team_id' => auth()->user()->currentTeam->id,
            'user_id' => auth()->id(),
            'expense_category_id' => $validatedData['category'],
            'amount' => $validatedData['amount'],
            'description' => $validatedData['description'] ?? null,
            'date' => $validatedData['date'],
        ];

        if ($validatedData['transaction_type'] === 'expense') {
            // 資金データの支払方法IDを設定
            $financeData['payment_method_id'] = $validatedData['payment_method'];

            // 支払方法の詳細をデータベースから取得
            $paymentMethod = PaymentMethod::find($validatedData['payment_method']);

            // 入力された日付をCarbonインスタンスに変換
            $inputDate = \Carbon\Carbon::parse($validatedData['date']);

            // 支払方法に締め日が設定されていない場合（現金の場合など）
            if (is_null($paymentMethod->closing_date)) {
                // 現金の場合は、反映日として入力日をそのまま使用
                $financeData['reflected_date'] = $inputDate;
            } else {
                // 入力日が締め日以前の場合
                if ($inputDate->day <= $paymentMethod->closing_date) {
                    // 月の初めに設定し、オフセット月を追加して反映日を計算
                    $reflectedDate = $inputDate->copy()->startOfMonth()->addMonths($paymentMethod->month_offset);
                } else {
                    // 入力日が締め日より後の場合
                    // 月の初めに設定し、オフセット月 + 1を追加して反映日を計算
                    $reflectedDate = $inputDate->copy()->startOfMonth()->addMonths($paymentMethod->month_offset + 1);
                }

                // 設定された支払日がその月の最大日数を超える場合、その月の最後の日を支払日として使用
                if ($paymentMethod->payment_date > $reflectedDate->daysInMonth) {
                    $reflectedDate->endOfMonth();
                } else {
                    // その月の指定された支払日に設定
                    $reflectedDate->day($paymentMethod->payment_date);
                }

                // 資金データの反映日を日の始まり（0時0分0秒）に設定
                $financeData['reflected_date'] = $reflectedDate->startOfDay();
            }
        } elseif ($validatedData['transaction_type'] === 'income') {
            // 収入の場合、支払い方法をnullに設定
            $financeData['payment_method_id'] = null;

            // 収入の場合の反映日のロジックをここに書く
            $financeData['reflected_date'] = \Carbon\Carbon::parse($validatedData['date']);
        }

        $finance->update($financeData);

        return redirect()->route('finance.index')->with('success', '更新に成功しました！');
    }

    public function destroy(Expense $finance)
    {
        $finance->delete();

        return redirect()->route('finance.index')->with('success', '家計簿データが削除されました。');
    }
}
