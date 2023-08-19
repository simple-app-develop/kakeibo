<?php

namespace App\Actions\Jetstream;

use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Laravel\Jetstream\Contracts\RemovesTeamMembers;
use Laravel\Jetstream\Events\TeamMemberRemoved;

/**
 * チームからメンバーを削除するクラス
 * 
 * このクラスは、Jetstreamを利用して、指定されたチームからメンバーを削除するロジックを提供します。
 */
class RemoveTeamMember implements RemovesTeamMembers
{
    /**
     * 指定されたチームからメンバーを削除するメソッド
     *
     * @param User $user チームのオーナーか管理者
     * @param Team $team メンバーを削除する対象のチーム
     * @param User $teamMember 削除するメンバーのユーザー情報
     */
    public function remove(User $user, Team $team, User $teamMember): void
    {
        $this->authorize($user, $team, $teamMember);

        $this->ensureUserDoesNotOwnTeam($teamMember, $team);

        $team->removeUser($teamMember);

        TeamMemberRemoved::dispatch($team, $teamMember);
    }

    /**
     * ユーザーがチームメンバーを削除できるかどうかを認可するメソッド
     *
     * @param User $user チームのオーナーか管理者
     * @param Team $team メンバーを削除する対象のチーム
     * @param User $teamMember 削除するメンバーのユーザー情報
     */
    protected function authorize(User $user, Team $team, User $teamMember): void
    {
        if (
            !Gate::forUser($user)->check('removeTeamMember', $team) &&
            $user->id !== $teamMember->id
        ) {
            throw new AuthorizationException;
        }
    }

    /**
     * 現在認証されているユーザーがチームのオーナーでないことを確認するメソッド
     *
     * @param User $teamMember 削除するメンバーのユーザー情報
     * @param Team $team メンバーを削除する対象のチーム
     */
    protected function ensureUserDoesNotOwnTeam(User $teamMember, Team $team): void
    {
        if ($teamMember->id === $team->owner->id) {
            throw ValidationException::withMessages([
                'team' => [__('You may not leave a team that you created.')],
            ])->errorBag('removeTeamMember');
        }
    }
}
