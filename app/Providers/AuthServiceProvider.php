<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        ResetPassword::toMailUsing(function ($notifiable, $token) {
            if ($notifiable instanceof User) {
                $callbackUrl = url(route('password.reset.user', [
                    'token' => $token,
                    'email' => $notifiable->email,
                ], false));
            } else {
                abort(403, 'Invalid user type');
            }

            $url = config('app.frontend_url').'/reset-password?callback_url=' .urlencode($callbackUrl);
            return (new MailMessage())
                ->subject('[KJ]  パスワード再登録手続きのご案内')
                ->greeting(' ')
                ->line('いつもKJをご利用いただきまして、誠にありがとうございます。')
                ->line('ご依頼いただきましたパスワードの再設定について次の通りご案内いたします。')
                ->line('■パスワード再設定')
                ->line('KJのパスワード再設定のお申し込みを承りました。')
                ->line('下記「パスワード再発行」のボタンをクリックし、新たにパスワードを設定し直してください。')
                ->action('パスワード再発行', $url)
                ->line('■ご注意')
                ->line('※「パスワード再発行」ボタンの有効期間は配信されてから60分です。')
                ->line('クリックしてもボタンが動作しない場合、以下URLをブラウザにコピーしてアクセスしてください。')
                ->line($url)
                ->line('なお、このメールの内容に覚えのない方は、お手数でございますが、破棄していただきますようお願い申し上げます。')
                ->salutation(' ');
        });
        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage())
            ->subject('[KJ] 会員登録のご案内')
            ->greeting('KJ 会員登録のご案内')
            ->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━')
            ->line('このたびはKJに会員登録いただきまして、誠にありがとうございます。')
            ->line('登録の手続きはまだ完了していません。以下の「登録を完了する」ボタンをクリックすると、登録が完了します。')
            ->action('登録を完了する', $url)
            ->line('登録完了すると、アカウントが有効になり、KJに参加できるようになります。')
            ->line('クリックしてもボタンが動作しない場合、以下URLをブラウザにコピーしてアクセスしてください。')
            ->line($url)
            ->line('なお、このメールの内容に覚えのない方は、お手数でございますが、破棄していただきますようお願い申し上げます。')
            ->salutation(' ');
        });
    }
}
