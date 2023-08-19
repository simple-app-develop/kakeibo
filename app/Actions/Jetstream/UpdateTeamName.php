<?php

namespace App\Actions\Jetstream;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Contracts\UpdatesTeamNames;

/**
 * チームの名前を更新するクラス
 * 
 * このクラスは、Jetstreamを利用して、指定されたチームの名前を更新するロジックを提供します。
 */
class UpdateTeamName implements UpdatesTeamNames
{
    /**
     * 指定されたチームの名前を検証し、更新するメソッド
     *
     * @param User $user チームのオーナーか管理者
     * @param Team $team 名前を更新する対象のチーム
     * @param array<string, string> $input 更新する名前の情報
     */
    public function update(User $user, Team $team, array $input): void
    {
        Gate::forUser($user)->authorize('update', $team);

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
        ])->validateWithBag('updateTeamName');

        $team->forceFill([
            'name' => $input['name'],
        ])->save();
    }
}
