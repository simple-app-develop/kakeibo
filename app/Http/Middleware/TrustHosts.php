<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustHosts as Middleware;

/**
 * 信頼するホストミドルウェアクラス
 * 
 * このミドルウェアは、アプリケーションで信頼するホストのパターンを定義します。
 * これにより、指定されたホスト以外からのリクエストを安全にブロックすることができます。
 */
class TrustHosts extends Middleware
{
    /**
     * 信頼するホストのパターンを取得します。
     * 
     * このメソッドは、アプリケーションのURLのすべてのサブドメインを信頼するホストとして返します。
     *
     * @return array<int, string|null> 信頼するホストのパターンのリスト
     */
    public function hosts(): array
    {
        return [
            $this->allSubdomainsOfApplicationUrl(),
        ];
    }
}
