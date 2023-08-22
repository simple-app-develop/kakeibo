<?php

namespace App\Services\Expenses;

use App\Models\ExpenseCategory;
use App\Models\PaymentMethod;

class ExpensePermissionService
{
    /**
     * Check if the user has permission to edit the category
     *
     * @param int $id Category ID
     * @return bool
     */
    public function checkPermission(string $modelType, int $id = null): bool
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

        if ($id) {
            switch ($modelType) {
                case 'category':
                    return ExpenseCategory::where('id', $id)
                        ->where('team_id', $teamId)
                        ->exists();
                case 'paymentMethod':
                    return PaymentMethod::where('id', $id)
                        ->where('team_id', $teamId)
                        ->exists();
            }
        }

        // IDが指定されていない場合（新規作成の場合）は、trueを返す
        return true;
    }
}
