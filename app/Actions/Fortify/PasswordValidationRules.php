<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Rules\Password;

/**
 * パスワードの検証ルールを提供するトレイト
 * 
 * このトレイトは、パスワードの検証ルールを提供するためのものです。
 * これにより、パスワードのバリデーションを一貫して適用することができます。
 */
trait PasswordValidationRules
{
    /**
     * パスワードを検証するためのルールを取得するメソッド
     *
     * このメソッドは、パスワードの検証ルールを返します。
     * 'required', 'string', 新しいPasswordルールおよび'confirmed'の4つのルールを適用します。
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array|string> パスワードの検証ルールの配列
     */
    protected function passwordRules(): array
    {
        return ['required', 'string', new Password, 'confirmed'];
    }
}
