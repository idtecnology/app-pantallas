<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        $data_gen = [
            'prev_url' => "/",
            'title' => 'Sube tu fotos o videos y publica con nosotros.'

        ];
        // Almacenar la URL anterior en la sesión
        Session::put('previousUrl', url()->previous());

        return view('auth.login', compact('data_gen'));
    }

    public function login(Request $request)
    {

        $data_user = User::where('email', '=', $request->email)->get();

        if (count($data_user) > 0) {
            $data_user = $data_user[0];
        } else {
            return redirect('login');
        }


        if (isset($data_user)) {
            if ($data_user->isActive != 1) {
                return redirect('login');
            }
            $data_gen = [
                'prev_url' => "/",
                'title' => 'Sube tu fotos o videos y publica con nosotros.'

            ];

            $this->validateLogin($request);


            if (
                method_exists($this, 'hasTooManyLoginAttempts') &&
                $this->hasTooManyLoginAttempts($request)
            ) {
                $this->fireLockoutEvent($request);

                return $this->sendLockoutResponse($request);
            }

            if ($this->attemptLogin($request)) {
                if ($request->hasSession()) {
                    $request->session()->put('auth.password_confirmed_at', time());
                }

                if (Auth::user()->hasVerifiedEmail()) {
                    return $this->sendLoginResponse($request);
                } else {
                    return view('auth.verify', compact('data_gen'));
                }
            }
            $this->incrementLoginAttempts($request);

            return $this->sendFailedLoginResponse($request);
        } else {
            return redirect('login');
        }
    }

    protected function authenticated(Request $request, $user)
    {
        return redirect('/'); // Personaliza la ruta de redirección después del inicio de sesión
    }
}
