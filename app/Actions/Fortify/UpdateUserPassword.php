<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;

/**
 * ユーザーのパスワードを更新するクラス
 * 
 * このクラスは、ユーザーの現在のパスワードを新しいパスワードに変更するロジックを提供します。
 */
class UpdateUserPassword implements UpdatesUserPasswords
{
    use PasswordValidationRules;

    /**
     * ユーザーのパスワードを検証して更新するメソッド
     *
     * このメソッドは、提供された現在のパスワードと新しいパスワードの入力を検証し、
     * ユーザーのパスワードを新しいものに更新します。
     * 
     * @param User $user パスワードを更新するユーザーのインスタンス
     * @param array<string, string> $input ユーザーからの入力 (現在のパスワードと新しいパスワード)
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'current_password' => ['required', 'string', 'current_password:web'],
            'password' => $this->passwordRules(),
        ], [
            'current_password.current_password' => __('The provided password does not match your current password.'),
        ])->validateWithBag('updatePassword');

        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();
    }
}
