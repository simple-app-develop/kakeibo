<?php

namespace App\Actions\Jetstream;

use App\Models\Team;
use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Contracts\AddsTeamMembers;
use Laravel\Jetstream\Events\AddingTeamMember;
use Laravel\Jetstream\Events\TeamMemberAdded;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\Rules\Role;

/**
 * チームに新しいメンバーを追加するクラス
 * 
 * このクラスは、Jetstreamを利用して、チームに新しいメンバーを追加するロジックを提供します。
 */
class AddTeamMember implements AddsTeamMembers
{
    /**
     * 指定されたチームに新しいメンバーを追加するメソッド
     *
     * @param User $user チームのオーナーか管理者
     * @param Team $team メンバーを追加する対象のチーム
     * @param string $email 追加するメンバーのメールアドレス
     * @param string|null $role メンバーの役割（オプション）
     */
    public function add(User $user, Team $team, string $email, string $role = null): void
    {
        Gate::forUser($user)->authorize('addTeamMember', $team);

        $this->validate($team, $email, $role);

        $newTeamMember = Jetstream::findUserByEmailOrFail($email);

        AddingTeamMember::dispatch($team, $newTeamMember);

        $team->users()->attach(
            $newTeamMember,
            ['role' => $role]
        );

        TeamMemberAdded::dispatch($team, $newTeamMember);
    }

    /**
     * メンバー追加操作を検証するメソッド
     *
     * @param Team $team メンバーを追加する対象のチーム
     * @param string $email 追加するメンバーのメールアドレス
     * @param string|null $role メンバーの役割
     */
    protected function validate(Team $team, string $email, ?string $role): void
    {
        Validator::make([
            'email' => $email,
            'role' => $role,
        ], $this->rules(), [
            'email.exists' => __('We were unable to find a registered user with this email address.'),
        ])->after(
            $this->ensureUserIsNotAlreadyOnTeam($team, $email)
        )->validateWithBag('addTeamMember');
    }

    /**
     * チームメンバー追加のための検証ルールを取得するメソッド
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string> 検証ルールの配列
     */
    protected function rules(): array
    {
        return array_filter([
            'email' => ['required', 'email', 'exists:users'],
            'role' => Jetstream::hasRoles()
                ? ['required', 'string', new Role]
                : null,
        ]);
    }

    /**
     * ユーザーが既にチームに存在していないことを確認するメソッド
     *
     * @param Team $team メンバーを追加する対象のチーム
     * @param string $email 追加するメンバーのメールアドレス
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
