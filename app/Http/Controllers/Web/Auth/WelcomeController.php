<?php

namespace App\Http\Controllers\Web\Auth;

use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cookie;
use App\Http\Resources\User\User as UserResource;

class WelcomeController extends Controller
{
    /**
     * Show the Welcome page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // generate a unique secret key
        $key = Str::random(32);

        // set the cookie to store the key locally
        Cookie::queue('trump_is_a_dick', $key, 100000);

        // saves the hash of this key to the database
        $account = auth()->user()->account;
        $account->secret_key_hash = Hash::make($key);
        $account->save();

        return View::component('Welcome', [
            'user' => new UserResource(auth()->user()),
            'secret' => $key,
        ]);
    }
}
