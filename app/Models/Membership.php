<?php

namespace App\Models;

use Laravel\Jetstream\Membership as JetstreamMembership;

/**
 * メンバーシップモデルクラス
 * 
 * このモデルは、Jetstreamのメンバーシップモデルを拡張しており、
 * アプリケーションのメンバーシップに関するデータを表現します。
 * メンバーシップは、ユーザーが所属するチームやグループの情報を持つものと考えることができます。
 */
class Membership extends JetstreamMembership
{
    /**
     * IDが自動インクリメントされるかどうかを示すフラグ
     *
     * このフラグがtrueの場合、IDは自動でインクリメントされます。
     * falseの場合、IDは手動で設定する必要があります。
     * 
     * @var bool
     */
    public $incrementing = true;
}
