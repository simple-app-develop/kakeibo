<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\ResetsUserPasswords;

/**
 * ユーザーのパスワードリセットを実行するクラス
 * 
 * このクラスは、ユーザーがパスワードを忘れた場合のリセットロジックを提供します。
 */
class ResetUserPassword implements ResetsUserPasswords
{
    use PasswordValidationRules;

    /**
     * ユーザーの忘れたパスワードを検証してリセットするメソッド
     *
     * このメソッドは、提供された入力を検証し、ユーザーのパスワードを新しいものにリセットします。
     * 
     * @param User $user パスワードをリセットするユーザーのインスタンス
     * @param array<string, string> $input ユーザーからの入力 (新しいパスワードなど)
     */
    public function reset(User $user, array $input): void
    {
        Validator::make($input, [
            'password' => $this->passwordRules(),
        ])->validate();

        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();
    }
}
