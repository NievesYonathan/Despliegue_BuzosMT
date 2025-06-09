<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::where('external_id', $googleUser->getId())
                    ->where('external_auth', 'google')
                    ->first();

        if (!$user) {
            // Si no existe, redirige a formulario para completar los datos
            return redirect()->route('register')->with([
                'user' => [
                    'email' => $googleUser->getEmail(),
                    'usu_nombres' => $googleUser->getName(),
                    'external_id' => $googleUser->getId(),
                ]
            ]);
        }

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
