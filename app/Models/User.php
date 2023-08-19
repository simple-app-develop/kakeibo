<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;

/**
 * ユーザーモデルクラス
 * 
 * このモデルは、アプリケーション内のユーザーに関連するデータを表現し、
 * 認証や権限のチェック、プロフィール画像の取り扱いなどの機能を提供します。
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;            // APIトークンの関連機能を提供
    use HasFactory;             // ファクトリーパターンに関連する機能を提供
    use HasProfilePhoto;        // プロフィール画像の関連機能を提供
    use HasTeams;               // チーム関連の機能を提供
    use Notifiable;             // 通知関連の機能を提供
    use TwoFactorAuthenticatable; // 2要素認証に関連する機能を提供

    /**
     * 代入可能な属性
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * シリアル化時に非表示にする属性
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * 属性の型変換を定義
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * モデルの配列形式への変換時に追加するアクセサ
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];
}
