<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

/**
 * CSRFトークン確認ミドルウェアクラス
 * 
 * このミドルウェアは、アプリケーションのCSRFトークンの確認を行います。
 * CSRF（クロスサイトリクエストフォージェリ）は、悪意のある攻撃者が無意識のユーザーとして
 * 操作を実行させるための攻撃手法です。このミドルウェアにより、そのような攻撃を防ぐことができます。
 */
class VerifyCsrfToken extends Middleware
{
    /**
     * CSRF検証から除外するべきURI
     * 
     * これらのURIに対するリクエストは、CSRFトークンの検証を受けません。
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];
}
