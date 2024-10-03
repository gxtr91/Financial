<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

#App
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('pages.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {


        $user = User::where('username', $request->name)->first();

        $checkPass = false;
        $active = false;
        if (!empty($user->username)) {
            $checkPass = Hash::check($request->password, $user->password);
            //$active = ($user->estado_usuario == 1 ? true : false);
        }

        if (empty($user->username) || !$checkPass) {
            return redirect('login')->with('error','Nombre de usuario o contraseña incorrectos.');
        }
        //if ($user->estado==0) {
          //  return redirect('login')->with('error','El usuario esta inactivo');
       // }

        Auth::login($user);

        $user = User::find(Auth::id());

        //$user->last_login = date('Y-m-d');

        //$user->save();

        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}