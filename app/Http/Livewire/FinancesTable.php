<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Expense;
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

    /**
     * コンポーネントのマウント時の処理
     */
    public function mount()
    {
        $this->month = Carbon::now()->month;
        $this->year = Carbon::now()->year;
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
            if (!$isFuture && $finance->payment_method_id !== null) {
                $total += $finance->amount;
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
            ->whereNull('payment_method_id')
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
            ->whereNotNull('payment_method_id')
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
            ->whereNotNull('payment_method_id')
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
            ->whereNull('payment_method_id')
            ->sum('amount');
    }

    /**
     * 今日までの支出合計を取得
     * 
     * @return float
     */
    public function getOverallExpense()
    {
        return Expense::where('team_id', auth()->user()->currentTeam->id)
            ->where('reflected_date', '<=', now())
            ->whereNotNull('payment_method_id')
            ->sum('amount');
    }

    /**
     * 今日までの総合計を取得
     * 
     * @return float
     */
    public function getOverallTotal()
    {
        return $this->getOverallIncome() - $this->getOverallExpense();
    }
}
