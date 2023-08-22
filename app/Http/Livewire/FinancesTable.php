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
            ->where('team_id', Auth::user()->currentTeam->id)  // <-- ここでteam_idを指定してデータをフィルタリング
            ->whereYear('date', $this->year)
            ->whereMonth('date', $this->month)
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
}
