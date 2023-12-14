<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Verifica tu email')
                ->view('vendor.notifications.verify', ['url' => $url]);
        });

        ResetPassword::toMailUsing(function (User $user,  string $token) {
            $email_endoce = urlencode($user->email);
            $url = env('APP_URL') . ':8000' . "/password/reset/$token?email=" . $email_endoce;

            return (new MailMessage)
                ->subject('Recupera tu contraseña')
                ->view('vendor.notifications.reset', ['url' => $url]);
        });


        // ResetPassword::createUrlUsing(function (User $user, string $token) {
        //     $email_endoce = urlencode($user->email);
        //     return env('APP_URL') . ':8000' . "/password/reset/?$token?email=" . $email_endoce;
        //     http: //localhost:8000/password/reset/95c2b47de63ba5c32d37765b8f499b7ba83ada0529cc5703c2479da2d1b8a0ff?email=jehfebles%40gmail.com
        // });
    }
}
