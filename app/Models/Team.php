<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Jetstream\Events\TeamCreated;
use Laravel\Jetstream\Events\TeamDeleted;
use Laravel\Jetstream\Events\TeamUpdated;
use Laravel\Jetstream\Team as JetstreamTeam;

/**
 * チームモデルクラス
 * 
 * このモデルは、Jetstreamのチームモデルを拡張しており、
 * アプリケーション内のチームやグループのデータを表現します。
 */
class Team extends JetstreamTeam
{
    use HasFactory;

    /**
     * 型変換をする属性
     *
     * この属性定義は、データベースの値をモデルの属性値に変換する際の型を指定します。
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'personal_team' => 'boolean',
    ];

    /**
     * 代入可能な属性
     *
     * この属性定義は、モデルのインスタンスを作成または更新する際に、一括で代入可能な属性を指定します。
     * 
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'personal_team',
    ];

    /**
     * モデルのイベントマップ
     *
     * この属性定義は、モデルの特定のイベントが発生したときに発火するイベントクラスを指定します。
     * 
     * @var array<string, class-string>
     */
    protected $dispatchesEvents = [
        'created' => TeamCreated::class,
        'updated' => TeamUpdated::class,
        'deleted' => TeamDeleted::class,
    ];
}
