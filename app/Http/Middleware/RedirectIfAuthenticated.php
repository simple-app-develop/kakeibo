<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * 既に認証済みの場合のリダイレクトミドルウェアクラス
 * 
 * このミドルウェアは、ユーザーがすでに認証済みの場合に指定したページにリダイレクトします。
 */
class RedirectIfAuthenticated
{
    /**
     * リクエストの処理ハンドラー
     * 
     * 既に認証済みの場合、RouteServiceProvider::HOME にリダイレクトします。
     * 認証されていない場合は、次のミドルウェアにリクエストを進めます。
     * 
     * @param Request $request 現在のリクエストインスタンス
     * @param Closure $next 次に実行すべきミドルウェアのクロージャ
     * @param string ...$guards 使用する認証ガードのリスト
     * @return Response レスポンスまたは次のミドルウェアの結果
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
