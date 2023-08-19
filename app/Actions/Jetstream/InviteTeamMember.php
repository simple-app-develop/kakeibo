<?php

namespace App\Actions\Jetstream;

use App\Models\Team;
use App\Models\User;
use Closure;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Jetstream\Contracts\InvitesTeamMembers;
use Laravel\Jetstream\Events\InvitingTeamMember;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\Mail\TeamInvitation;
use Laravel\Jetstream\Rules\Role;

/**
 * チームに新しいメンバーを招待するクラス
 * 
 * このクラスは、Jetstreamを利用して、指定されたチームに新しいメンバーを招待するロジックを提供します。
 */
class InviteTeamMember implements InvitesTeamMembers
{
    /**
     * 指定されたチームに新しいメンバーを招待するメソッド
     *
     * @param User $user チームのオーナーか管理者
     * @param Team $team メンバーを招待する対象のチーム
     * @param string $email 招待するメンバーのメールアドレス
     * @param string|null $role メンバーの役割（オプション）
     */
    public function invite(User $user, Team $team, string $email, string $role = null): void
    {
        Gate::forUser($user)->authorize('addTeamMember', $team);

        $this->validate($team, $email, $role);

        InvitingTeamMember::dispatch($team, $email, $role);

        $invitation = $team->teamInvitations()->create([
            'email' => $email,
            'role' => $role,
        ]);

        Mail::to($email)->send(new TeamInvitation($invitation));
    }

    /**
     * メンバー招待操作を検証するメソッド
     *
     * @param Team $team メンバーを招待する対象のチーム
     * @param string $email 招待するメンバーのメールアドレス
     * @param string|null $role メンバーの役割
     */
    protected function validate(Team $team, string $email, ?string $role): void
    {
        Validator::make([
            'email' => $email,
            'role' => $role,
        ], $this->rules($team), [
            'email.unique' => __('This user has already been invited to the team.'),
        ])->after(
            $this->ensureUserIsNotAlreadyOnTeam($team, $email)
        )->validateWithBag('addTeamMember');
    }

    /**
     * チームメンバー招待のための検証ルールを取得するメソッド
     *
     * @param Team $team メンバーを招待する対象のチーム
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string> 検証ルールの配列
     */
    protected function rules(Team $team): array
    {
        return array_filter([
            'email' => [
                'required', 'email',
                Rule::unique('team_invitations')->where(function (Builder $query) use ($team) {
                    $query->where('team_id', $team->id);
                }),
            ],
            'role' => Jetstream::hasRoles()
                ? ['required', 'string', new Role]
                : null,
        ]);
    }

    /**
     * ユーザーが既にチームに存在していないことを確認するメソッド
     *
     * @param Team $team メンバーを招待する対象のチーム
     * @param string $email 招待するメンバーのメールアドレス
     * @return Closure クロージャーで、存在している場合にバリデーションエラーを追加する
     */
    protected function ensureUserIsNotAlreadyOnTeam(Team $team, string $email): Closure
    {
        return function ($validator) use ($team, $email) {
            $validator->errors()->addIf(
                $team->hasUserWithEmail($email),
                'email',
                __('This user already belongs to the team.')
            );
        };
    }
}
