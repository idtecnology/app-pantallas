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
                ->from('no-responder@adsupp.com', 'AdsUpp')
                ->view('vendor.notifications.verify', ['url' => $url]);
        });

        ResetPassword::toMailUsing(function (User $user,  string $token) {
            $email_endoce = urlencode($user->email);
            $url = env('APP_URL') . "/password/reset/$token?email=" . $email_endoce;

            return (new MailMessage)
                ->subject('Recupera tu contraseña')
                ->from('no-responder@adsupp.com', 'AdsUpp')
                ->view('vendor.notifications.reset', ['url' => $url]);
        });
    }
}
