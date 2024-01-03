<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;

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
            $rdn = Str::random(64);

            $user_data = User::find(auth()->user()->id);
            $user_data->verify_hash = sha1($rdn);
            $user_data->save();

            $urls = URL::temporarySignedRoute(
                'verification.verify',
                '',
                [
                    'id' => $notifiable->getKey(),
                    'hash' => sha1($rdn),
                ]
            );

            return (new MailMessage)
                ->subject('Verifica tu email')
                ->from('no-responder@adsupp.com', 'AdsUpp')
                ->view('vendor.notifications.verify', ['url' => $urls]);
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
