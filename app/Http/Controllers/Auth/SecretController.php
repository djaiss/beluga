<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;

class SecretController extends Controller
{
    /**
     * Show the Set secret page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::component('SetSecretKey');
    }

    /**
     * Set the secret key that will be used to encrypt all data.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'secret' => 'required|string|size:32',
        ])->validate();

        // let's use a funny cookie name so people don't guess right away
        // what this value means in their browser.
        Cookie::queue('trump_is_a_dick', $request->get('secret'), 100000);

        return redirect('/home');
    }
}
