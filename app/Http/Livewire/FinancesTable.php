<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Expense;
use Carbon\Carbon;

class FinancesTable extends Component
{
    public $month;
    public $year;

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
            ->whereYear('date', $this->year)
            ->whereMonth('date', $this->month)
            ->get();

        return view('expenses.finance.finances-table', compact('finances'));
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
}
