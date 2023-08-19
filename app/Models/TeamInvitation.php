<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\TeamInvitation as JetstreamTeamInvitation;

/**
 * チーム招待モデルクラス
 * 
 * このモデルは、Jetstreamのチーム招待モデルを拡張しており、
 * アプリケーション内のチームへの招待に関するデータを表現します。
 */
class TeamInvitation extends JetstreamTeamInvitation
{
    /**
     * 代入可能な属性
     *
     * この属性定義は、モデルのインスタンスを作成または更新する際に、一括で代入可能な属性を指定します。
     * 
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'role',
    ];

    /**
     * 招待が属するチームを取得します。
     * 
     * @return BelongsTo チームへの関連を示すBelongsToインスタンス
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Jetstream::teamModel());
    }
}
