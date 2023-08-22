<?php

namespace App\Actions\Expenses\Finance;

class DeleteFinance
{
    public function run($finance)
    {
        $finance->delete();
    }
}
