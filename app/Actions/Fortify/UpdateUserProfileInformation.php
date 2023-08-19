<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

/**
 * ユーザーのプロフィール情報を更新するクラス
 * 
 * このクラスは、ユーザーのプロフィール情報を更新するためのロジックを提供します。
 */
class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * 与えられたユーザーのプロフィール情報を検証して更新するメソッド
     *
     * @param User $user プロフィール情報を更新するユーザーのインスタンス
     * @param array<string, string> $input ユーザーからの入力 (名前、メール、写真など)
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        if (
            $input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail
        ) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'name' => $input['name'],
                'email' => $input['email'],
            ])->save();
        }
    }

    /**
     * 与えられた検証済みのユーザーのプロフィール情報を更新するメソッド
     *
     * @param User $user プロフィール情報を更新するユーザーのインスタンス
     * @param array<string, string> $input ユーザーからの入力 (名前、メールなど)
     */
    protected function updateVerifiedUser(User $user, array $input): void
    {
        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
