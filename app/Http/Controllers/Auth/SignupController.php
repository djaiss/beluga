<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Services\Account\CreateAccount;
use Illuminate\Foundation\Auth\ThrottlesLogins;

class SignupController extends Controller
{
    use ThrottlesLogins;

    /**
     * Show the register page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::check()) {
            return redirect('/home');
        }

        return View::component('Register');
    }

    /**
     * Store the user.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = [
            'email' => $request->get('email'),
            'password' => $request->get('password'),
        ];

        (new CreateAccount)->execute($data);

        Auth::attempt([
            'email' => $request->get('email'),
            'password' => $request->get('password'),
        ]);
    }
}
