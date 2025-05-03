<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
            //$user = User::where('google_id', $googleUser->id); doesnt work because we need actual user model, we get that using .first()
            $user = User::where('google_id', $googleUser->id)->first();
            if ($user) {
                Auth::login($user);
                return redirect()->route('dashboard');
            } else {
                // User does not exist, create a new user
                $userData = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => Hash::make('Password@1234'), // Generate a random password
                ]);

                if ($userData) {
                    Auth::login($userData);
                    return redirect()->route('dashboard');
                } else {
                    return redirect()->route('auth.google')->with('error', 'Failed to create user.');
                }
            }
            //dd($googleUser);
        } catch (InvalidStateException $e) {
            dd('Invalid state: ' . $e->getMessage());
        } catch (Exception $e) {
            dd($e);
        }
    }
}
