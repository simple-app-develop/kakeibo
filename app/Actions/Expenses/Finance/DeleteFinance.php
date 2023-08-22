<?php

namespace App\Actions\Expenses\Finance;

class DeleteFinance
{
    public function delete($finance)
    {
        $finance->delete();
    }
}
