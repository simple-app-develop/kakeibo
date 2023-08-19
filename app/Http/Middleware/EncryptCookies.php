<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

/**
 * クッキー暗号化ミドルウェアクラス
 * 
 * このミドルウェアは、クッキーを暗号化するためのものです。
 * 特定のクッキー名を暗号化から除外したい場合は、$exceptプロパティにそのクッキー名を追加します。
 */
class EncryptCookies extends Middleware
{
    /**
     * 暗号化しないクッキーの名前のリスト
     *
     * @var array<int, string>
     */
    protected $except = [
        // ここに暗号化しないクッキーの名前を追加
    ];
}
