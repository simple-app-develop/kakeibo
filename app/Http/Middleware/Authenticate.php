<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

/**
 * 認証ミドルウェアクラス
 * 
 * ユーザーが認証されていない場合に、適切なリダイレクト先へと導くミドルウェアです。
 */
class Authenticate extends Middleware
{
    /**
     * 認証されていないユーザーがアクセスしようとした場合のリダイレクト先を取得するメソッド
     * 
     * リクエストがJSONを期待する場合はnullを返し、それ以外の場合はログインルートへとリダイレクトします。
     *
     * @param Request $request 現在のリクエストインスタンス
     * @return string|null リダイレクト先のURLもしくはnull
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }
}
