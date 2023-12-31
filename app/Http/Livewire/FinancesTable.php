<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Expense;
use App\Models\Wallet;
use App\Services\Expenses\ExpensePermissionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * 月ごとの家計簿データテーブルを扱うLivewireコンポーネント
 */
class FinancesTable extends Component
{
    /** @var int $month 対象とする月 */
    public $month;

    /** @var int $year 対象とする年 */
    public $year;

    /** @var float $totalAmount 月の合計金額 */
    public $totalAmount = 0;

    // 
    public $permissions = [false, false, false, false];



    //
    public $scheduledExpenseDetails = [];

    //
    public $showScheduledExpenseDetails = false;

    //  
    public $showScheduledIncomeDetails = false;

    //
    public $scheduledIncomeDetails = [];

    /**
     * コンポーネントのマウント時の処理
     *
     * @param ExpensePermissionService $expensePermissionService Permissionサービス
     */
    public function mount(ExpensePermissionService $expensePermissionService)
    {
        $this->month = Carbon::now()->month;
        $this->year = Carbon::now()->year;

        // 権限を確認する
        $this->permissions = [
            'canRead' => $expensePermissionService->checkPermission('finance', 'read'),
            'canUpdate' => $expensePermissionService->checkPermission('finance', 'update'),
            'canDelete' => $expensePermissionService->checkPermission('finance', 'delete'),
            'canCreate' => $expensePermissionService->checkPermission('finance', 'create')
        ];
    }

    /**
     * コンポーネントをレンダリング
     * 
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $startDate = Carbon::create($this->year, $this->month, 1);
        $endDate = $startDate->copy()->endOfMonth();

        $finances = Expense::with(['expense_category', 'payment_method'])
            ->where('team_id', Auth::user()->currentTeam->id)
            ->whereYear('date', $this->year)
            ->whereMonth('date', $this->month)
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // 計算処理をコンポーネント内で実行
        $this->totalAmount = $this->calculateTotal($finances);

        return view('expenses.finance.finances-table', compact('finances'));
    }

    /**
     * 与えられた家計簿データの合計金額を計算
     * 
     * @param \Illuminate\Support\Collection $finances
     * @return float
     */
    public function calculateTotal($finances)
    {
        $total = 0;
        foreach ($finances as $finance) {
            $isFuture = \Carbon\Carbon::parse($finance->reflected_date)->isFuture();
            if (!$isFuture) {
                if ($finance->type == 'expense') {
                    $total -= $finance->amount;
                } else {
                    $total += $finance->amount;
                }
            }
        }
        return $total;
    }


    /**
     * 月を増やす
     */
    public function incrementMonth()
    {
        $date = Carbon::create($this->year, $this->month, 1)->addMonth();
        $this->month = $date->month;
        $this->year = $date->year;
    }

    /**
     * 月を減らす
     */
    public function decrementMonth()
    {
        $date = Carbon::create($this->year, $this->month, 1)->subMonth();
        $this->month = $date->month;
        $this->year = $date->year;
    }

    /**
     * 現在の年と月を取得
     * 
     * @return string
     */
    public function getCurrentMonthYear()
    {
        return Carbon::create($this->year, $this->month)->format('Y年m月');
    }

    /**
     * 日付が過去かどうかを判断
     * 
     * @param string $date
     * @return bool
     */
    public function isPastReflectedDate($date)
    {
        return \Carbon\Carbon::parse($date)->isPast();
    }

    /**
     * 月の収入合計を取得
     * 
     * @return float
     */
    public function getTotalIncome()
    {
        return Expense::where('team_id', auth()->user()->currentTeam->id)
            ->whereYear('reflected_date', $this->year)
            ->whereMonth('reflected_date', $this->month)
            ->where('reflected_date', '<', now()) // 今日の日付より前のもののみ
            ->where('type', 'income')
            ->sum('amount');
    }

    /**
     * 予定された月の収入合計を取得
     * 
     * @return float
     */
    public function getScheduledIncome()
    {
        return Expense::where('team_id', auth()->user()->currentTeam->id)
            ->whereYear('reflected_date', $this->year)
            ->whereMonth('reflected_date', $this->month)
            ->where('reflected_date', '>=', now()) // 今日の日付以降のもののみ
            ->where('type', 'income')
            ->sum('amount');
    }


    /**
     * 月の支出合計を取得
     * 
     * @return float
     */
    public function getTotalExpense()
    {
        return Expense::where('team_id', auth()->user()->currentTeam->id)
            ->whereYear('reflected_date', $this->year)
            ->whereMonth('reflected_date', $this->month)
            ->where('reflected_date', '<', now()) // 今日の日付より前のもののみ
            ->where('type', 'expense')
            ->sum('amount');
    }

    /**
     * 予定された月の支出合計を取得
     * 
     * @return float
     */
    public function getScheduledExpense()
    {
        return Expense::where('team_id', auth()->user()->currentTeam->id)
            ->whereYear('reflected_date', $this->year)
            ->whereMonth('reflected_date', $this->month)
            ->where('reflected_date', '>=', now()) // 今日の日付以降のもののみ
            ->where('type', 'expense')
            ->sum('amount');
    }

    /**
     * 今日までの収入合計を取得
     * 
     * @return float
     */
    public function getOverallIncome()
    {
        return Expense::where('team_id', auth()->user()->currentTeam->id)
            ->where('reflected_date', '<=', now())
            ->where('type', 'income')
            ->sum('amount');
    }

    /**
     * 今日までの支出合計を取得
     * 
     * @return float
     */
    public function getOverallExpense()
    {
        // 通常の支出
        $expenseTotal = Expense::where('team_id', auth()->user()->currentTeam->id)
            ->where('reflected_date', '<=', now())
            ->where('type', 'expense')
            ->sum('amount');

        // 残高移動で引き出された合計
        $transferOutTotal = Expense::where('team_id', auth()->user()->currentTeam->id)
            ->where('reflected_date', '<=', now())
            ->where('type', 'transfer')
            ->whereNotNull('wallet_id')
            ->sum('amount');

        return $expenseTotal + $transferOutTotal;
    }



    /**
     * 今日までの総合計を取得
     * 
     * @return float
     */
    public function getOverallTotal()
    {
        $initialBalances = Wallet::where('team_id', Auth::user()->currentTeam->id)->sum('balance');

        return $initialBalances + $this->getOverallIncome() - $this->getOverallExpense();
    }


    // トグル表示を制御するメソッド
    public function toggleScheduledExpenseDetails()
    {
        if (!$this->showScheduledExpenseDetails) {
            $this->scheduledExpenseDetails = Expense::where('team_id', auth()->user()->currentTeam->id)
                ->whereYear('reflected_date', $this->year)
                ->whereMonth('reflected_date', $this->month)
                ->where('reflected_date', '>=', now())
                ->whereNotNull('payment_method_id')
                ->get();
        }
        $this->showScheduledExpenseDetails = !$this->showScheduledExpenseDetails;
    }

    /**
     * 予定された支出の詳細を取得
     *
     * @return \Illuminate\Support\Collection
     */
    public function getScheduledExpenseDetails()
    {
        return Expense::where('team_id', auth()->user()->currentTeam->id)
            ->whereYear('reflected_date', $this->year)
            ->whereMonth('reflected_date', $this->month)
            ->where('reflected_date', '>=', now()) // 今日の日付以降のもののみ
            ->where('type', 'expense')
            ->with('expense_category') // <--- 項目名を取得するためのリレーション
            ->get();
    }

    public function toggleScheduledIncomeDetails()
    {
        if (!$this->showScheduledIncomeDetails) {
            $this->scheduledIncomeDetails = Expense::where('team_id', auth()->user()->currentTeam->id)
                ->whereYear('reflected_date', $this->year)
                ->whereMonth('reflected_date', $this->month)
                ->where('reflected_date', '>=', now())
                ->where('type', 'income')
                ->get();
        }
        $this->showScheduledIncomeDetails = !$this->showScheduledIncomeDetails;
    }

    /**
     * 対象の家計簿データに基づいてテキストの色を取得します。
     * 
     * @param \App\Models\Expense $finance
     * @return string
     */
    public function getTextColor($finance): string
    {
        $currentViewMonth = Carbon::parse($this->getCurrentMonthYearForCarbon())->format('m');

        if ($finance->type == 'income') {
            if (Carbon::parse($finance->date)->isFuture()) {
                return 'text-green-400 font-light';  // 未来の収入は薄い緑に
            } elseif (Carbon::parse($finance->date)->format('m') == $currentViewMonth) {
                return 'text-green-700 font-semibold'; // 現在の月の収入は濃い緑に
            }
            // 過去の月の収入は---に
        }



        if ($finance->type == 'expense') {
            if (Carbon::parse($finance->reflected_date)->isFuture() && Carbon::parse($finance->reflected_date)->format('m') == $currentViewMonth) {
                return 'text-gray-400 font-semibold'; // 未来の反映日で、現在の月のもの
            }
            if (Carbon::parse($finance->reflected_date)->format('m') != $currentViewMonth) {
                return 'text-gray-300 '; // 現在の月外のもの
            }
            return 'font-bold'; // それ以外
        }


        return 'font-bold';
    }


    /**
     * 現在の年と月をCarbonフォーマットで取得
     * 
     * @return string
     */
    public function getCurrentMonthYearForCarbon(): string
    {
        return str_replace('年', '-', str_replace('月', '', $this->getCurrentMonthYear()));
    }

    /**
     * それぞれの財布の残高を取得
     *
     * @return array
     */
    public function getWalletBalances()
    {
        $teamId = Auth::user()->currentTeam->id;

        // チームに関連する財布のみを取得
        $wallets = Wallet::where('team_id', $teamId)->get();

        $balances = [];
        foreach ($wallets as $wallet) {
            // 初期残高をセット
            $balance = $wallet->balance;

            // この財布の収入合計
            $balance += Expense::where('team_id', $teamId)
                ->where('wallet_id', $wallet->id)
                ->whereDate('reflected_date', '<=', now())
                ->where('type', 'income')
                ->sum('amount');

            // この財布の支出合計
            $balance -= Expense::where('team_id', $teamId)
                ->whereHas('payment_method', function ($query) use ($wallet) {
                    $query->where('wallet_id', $wallet->id);
                })->whereDate('reflected_date', '<=', now())
                ->where('type', 'expense')
                ->sum('amount');

            // この財布への残高移動入金
            $balance += Expense::where('team_id', $teamId)
                ->where('target_wallet_id', $wallet->id)
                ->where('type', 'transfer')
                ->where('reflected_date', '<=', now())
                ->sum('amount');

            // この財布からの残高移動引き出し
            $balance -= Expense::where('team_id', $teamId)
                ->where('wallet_id', $wallet->id)
                ->where('type', 'transfer')
                ->where('reflected_date', '<=', now())
                ->sum('amount');

            $balances[$wallet->name] = $balance;
        }

        return $balances;
    }
}
