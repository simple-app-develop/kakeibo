<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

/**
 * ローカライゼーションミドルウェアクラス
 * 
 * このミドルウェアは、アプリケーションのローカライゼーション設定を
 * セッションから取得して適用します。
 */
class Localization
{
    /**
     * リクエストの処理ハンドラー
     * 
     * セッションからロケール情報を取得し、アプリケーションのロケールを設定します。
     * 
     * @param Request $request 現在のリクエストインスタンス
     * @param Closure $next 次に実行すべきミドルウェアのクロージャ
     * @return mixed レスポンスまたは次のミドルウェアの結果
     */
    public function handle(Request $request, Closure $next)
    {
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }

        return $next($request);
    }
}
