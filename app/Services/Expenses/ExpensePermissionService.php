<?php

namespace App\Services\Expenses;

use App\Models\ExpenseCategory;
use App\Models\PaymentMethod;

class ExpensePermissionService
{
    protected $permissions = [
        'finance' => [
            'read' => 'read',
            'update' => 'update',
            'delete' => 'delete',
            'create' => 'create'
        ],
        'category' => [
            'read' => 'read',
            'update' => 'update',
            'delete' => 'delete',
            'create' => 'create'
        ],
        'paymentMethod' => [
            'read' => 'read',
            'update' => 'update',
            'delete' => 'delete',
            'create' => 'create'
        ],
        'wallet' => [
            'read' => 'read',
            'update' => 'update',
            'delete' => 'delete',
            'create' => 'create'
        ],
    ];

    /**
     * Check if the user has a specific permission.
     *
     * @param string $modelType
     * @param string $action
     * @param int|null $id
     * @return bool
     */
    public function checkPermission(string $modelType, string $action, int $id = null): bool
    {
        /** @var \App\Models\User|null */
        $user = auth()->user();

        if (!$user) {
            return false;
        }

        $teamId = $user->currentTeam->id;

        // Check if user has permission based on the provided model type and action.
        if (!$user->hasTeamPermission($user->currentTeam, $this->permissions[$modelType][$action])) {
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
