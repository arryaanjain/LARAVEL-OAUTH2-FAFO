<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class SocialiteController extends Controller
{
    //redirect to google 
    public function googleLogin()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Authenticate the user with Google.
     * @param NA
     * @return void
     */
    public function googleAuthentication()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            dd($googleUser);
        } catch (InvalidStateException $e) {
            dd('Invalid state: ' . $e->getMessage());
        } catch (\Exception $e) {
            dd('OAuth error: ' . $e->getMessage());
        }
    }
}
