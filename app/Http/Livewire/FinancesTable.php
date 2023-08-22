<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class FinancesTable extends Component
{
    public $month;
    public $year;
    public $totalAmount = 0;

    public function mount()
    {
        $this->month = Carbon::now()->month;
        $this->year = Carbon::now()->year;
    }

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

    public function incrementMonth()
    {
        $date = Carbon::create($this->year, $this->month, 1)->addMonth();
        $this->month = $date->month;
        $this->year = $date->year;
    }

    public function decrementMonth()
    {
        $date = Carbon::create($this->year, $this->month, 1)->subMonth();
        $this->month = $date->month;
        $this->year = $date->year;
    }

    public function getCurrentMonthYear()
    {
        return Carbon::create($this->year, $this->month)->format('Y年m月');
    }

    public function isPastReflectedDate($date)
    {
        return \Carbon\Carbon::parse($date)->isPast();
    }

    public function getTotalIncome()
    {
        // 当月内で、計上日が当月かつ支払い方法がnull（収入）
        return Expense::where('team_id', auth()->user()->currentTeam->id)
            ->whereYear('reflected_date', $this->year)
            ->whereMonth('reflected_date', $this->month)
            ->whereNull('payment_method_id')
            ->sum('amount');
    }

    public function getTotalExpense()
    {
        // 計上日が当月で、かつ、今日より前のものを対象として合計
        return Expense::where('team_id', auth()->user()->currentTeam->id)
            ->whereYear('reflected_date', $this->year)
            ->whereMonth('reflected_date', $this->month)
            ->where('reflected_date', '<', now()) // 今日の日付より前のもののみ
            ->whereNotNull('payment_method_id')
            ->sum('amount');
    }


    public function getScheduledExpense()
    {
        // `reflected_date` が当月、かつ、まだ未来のものを対象とします。
        return Expense::where('team_id', auth()->user()->currentTeam->id)
            ->whereYear('reflected_date', $this->year)
            ->whereMonth('reflected_date', $this->month)
            ->where('reflected_date', '>=', now()) // 今日の日付以降のもののみ
            ->whereNotNull('payment_method_id')
            ->sum('amount');
    }


    public function getOverallIncome()
    {
        // 今日以前の計上日で収入を合計
        return Expense::where('team_id', auth()->user()->currentTeam->id)
            ->where('reflected_date', '<=', now())
            ->whereNull('payment_method_id')
            ->sum('amount');
    }

    public function getOverallExpense()
    {
        // 今日以前の計上日で支出を合計
        return Expense::where('team_id', auth()->user()->currentTeam->id)
            ->where('reflected_date', '<=', now())
            ->whereNotNull('payment_method_id')
            ->sum('amount');
    }

    public function getOverallTotal()
    {
        return $this->getOverallIncome() - $this->getOverallExpense();
    }
}
