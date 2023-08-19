<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * チームポリシークラス
 * 
 * このクラスは、チームモデルに関連する認可ロジックを定義します。
 * それぞれのメソッドは、特定のアクションがユーザーによって実行可能かどうかを判断するためのものです。
 */
class TeamPolicy
{
    use HandlesAuthorization;

    /**
     * ユーザーが任意のモデルを閲覧できるか判断します。
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * ユーザーが指定されたチームモデルを閲覧できるか判断します。
     */
    public function view(User $user, Team $team): bool
    {
        return $user->belongsToTeam($team);
    }

    /**
     * ユーザーが新しいモデルを作成できるか判断します。
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * ユーザーが指定されたチームモデルを更新できるか判断します。
     */
    public function update(User $user, Team $team): bool
    {
        return $user->ownsTeam($team);
    }

    /**
     * ユーザーがチームメンバーを追加できるか判断します。
     */
    public function addTeamMember(User $user, Team $team): bool
    {
        return $user->ownsTeam($team);
    }

    /**
     * ユーザーがチームメンバーの権限を更新できるか判断します。
     */
    public function updateTeamMember(User $user, Team $team): bool
    {
        return $user->ownsTeam($team);
    }

    /**
     * ユーザーがチームメンバーを削除できるか判断します。
     */
    public function removeTeamMember(User $user, Team $team): bool
    {
        return $user->ownsTeam($team);
    }

    /**
     * ユーザーが指定されたチームモデルを削除できるか判断します。
     */
    public function delete(User $user, Team $team): bool
    {
        return $user->ownsTeam($team);
    }
}
