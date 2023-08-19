<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

/**
 * 信頼するプロキシミドルウェアクラス
 * 
 * このミドルウェアは、アプリケーションの信頼するプロキシを定義します。
 * 信頼するプロキシを使用することで、リバースプロキシ背後でのLaravelアプリケーションの動作を正しくすることができます。
 */
class TrustProxies extends Middleware
{
    /**
     * アプリケーションの信頼するプロキシ
     *
     * @var array<int, string>|string|null
     */
    protected $proxies;

    /**
     * プロキシを検出するために使用するヘッダー
     * 
     * この属性は、リクエストがプロキシを通過したことを示すヘッダーのビットマスクとして定義されます。
     *
     * @var int
     */
    protected $headers = Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;
}
