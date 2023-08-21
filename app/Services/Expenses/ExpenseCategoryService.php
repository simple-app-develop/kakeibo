<?php

namespace App\Services\Expenses;

use App\Models\ExpenseCategory;
use Laravel\Jetstream\HasTeams;

class ExpenseCategoryService
{
    /**
     * Check if the user has permission to edit the category
     *
     * @param int $id Category ID
     * @return bool
     */
    public function checkPermission(int $id): bool
    {

        /** @var \App\Models\User|null */
        $user = auth()->user();

        if (!$user) {
            return false;
        }

        $teamId = $user->currentTeam->id;

        // ユーザーが 'viewer' のロールだけを持っている場合は、アクセスを制限
        if (
            $user->hasTeamRole($user->currentTeam, 'viewer') &&
            !$user->hasTeamRole($user->currentTeam, 'administrator') &&
            !$user->hasTeamRole($user->currentTeam, 'editor')
        ) {
            return false;
        }


        // ここで $teamId を直接使用して、ExpenseCategory のチェックを行います
        return ExpenseCategory::where('id', $id)
            ->where('team_id', $teamId)
            ->exists();
    }
}
