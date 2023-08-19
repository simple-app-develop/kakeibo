<?php

namespace App\Actions\Jetstream;

use App\Models\Team;
use Laravel\Jetstream\Contracts\DeletesTeams;

/**
 * チームを削除するクラス
 * 
 * このクラスは、Jetstreamを利用して、指定されたチームを削除するロジックを提供します。
 */
class DeleteTeam implements DeletesTeams
{
    /**
     * 指定されたチームを削除するメソッド
     *
     * @param Team $team 削除するチームのインスタンス
     */
    public function delete(Team $team): void
    {
        $team->purge();
    }
}
