<?php

namespace App\Actions\Jetstream;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Laravel\Jetstream\Contracts\DeletesTeams;
use Laravel\Jetstream\Contracts\DeletesUsers;

/**
 * ユーザーを削除するクラス
 * 
 * このクラスは、Jetstreamを利用して、指定されたユーザーを削除するロジックを提供します。
 * ユーザーが関連付けられているチームやチームの関連情報も削除します。
 */
class DeleteUser implements DeletesUsers
{
    /**
     * チーム削除の実装
     *
     * @var \Laravel\Jetstream\Contracts\DeletesTeams
     */
    protected $deletesTeams;

    /**
     * 新しいアクションインスタンスを作成するコンストラクタ
     *
     * @param DeletesTeams $deletesTeams チーム削除の実装
     */
    public function __construct(DeletesTeams $deletesTeams)
    {
        $this->deletesTeams = $deletesTeams;
    }

    /**
     * 指定されたユーザーを削除するメソッド
     *
     * @param User $user 削除するユーザーのインスタンス
     */
    public function delete(User $user): void
    {
        DB::transaction(function () use ($user) {
            $this->deleteTeams($user);
            $user->deleteProfilePhoto();
            $user->tokens->each->delete();
            $user->delete();
        });
    }

    /**
     * ユーザーに関連付けられたチームとチームの関連情報を削除するメソッド
     *
     * @param User $user チーム関連情報を削除するユーザーのインスタンス
     */
    protected function deleteTeams(User $user): void
    {
        $user->teams()->detach();

        $user->ownedTeams->each(function (Team $team) {
            $this->deletesTeams->delete($team);
        });
    }
}
