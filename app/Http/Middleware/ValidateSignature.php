<?php

namespace App\Http\Middleware;

use Illuminate\Routing\Middleware\ValidateSignature as Middleware;

/**
 * 署名検証ミドルウェアクラス
 * 
 * このミドルウェアは、URLの署名が正しいことを検証します。
 * 署名検証により、特定のURLが改ざんされていないことを確認することができます。
 */
class ValidateSignature extends Middleware
{
    /**
     * 無視するクエリ文字列パラメータの名前
     *
     * 署名検証時に、これらのクエリ文字列パラメータは無視されます。
     * 例えば、外部のトラッキングサービスから追加される可能性のあるものを指定します。
     * 
     * @var array<int, string>
     */
    protected $except = [
        // 'fbclid',
        // 'utm_campaign',
        // 'utm_content',
        // 'utm_medium',
        // 'utm_source',
        // 'utm_term',
    ];
}
