<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as Middleware;

/**
 * メンテナンスモード時のリクエスト防止ミドルウェアクラス
 * 
 * このミドルウェアは、アプリケーションがメンテナンスモードのときにリクエストを防止します。
 * 特定のURIをメンテナンスモード中にもアクセス可能にしたい場合は、$exceptプロパティにそのURIを追加します。
 */
class PreventRequestsDuringMaintenance extends Middleware
{
    /**
     * メンテナンスモード中にもアクセス可能なURIのリスト
     *
     * @var array<int, string>
     */
    protected $except = [
        // ここにメンテナンスモード中にもアクセス可能なURIを追加
    ];
}
