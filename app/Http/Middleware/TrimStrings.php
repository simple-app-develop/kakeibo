<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;

/**
 * 文字列トリムミドルウェアクラス
 * 
 * このミドルウェアは、リクエストの文字列入力の前後の空白をトリム（削除）します。
 * ただし、$exceptプロパティで指定された属性の文字列はトリムされません。
 */
class TrimStrings extends Middleware
{
    /**
     * トリムしない属性の名前のリスト
     *
     * @var array<int, string>
     */
    protected $except = [
        'current_password',
        'password',
        'password_confirmation',
    ];
}
