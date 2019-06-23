<?php

namespace App\Http\Controllers\Web\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cookie;
use App\Services\Account\VerifySecretKey;
use App\Exceptions\NotTheRightSecretKeyException;

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
        try {
            (new VerifySecretKey)->execute([
                'account_id' => auth()->user()->account_id,
                'secret_key' => trim($request->get('secret')),
            ]);
        } catch (NotTheRightSecretKeyException $e) {
            return response()->json([
                'errors' => 'Invalid key',
            ], 403);
        }

        // let's use a... different cookie name so people don't guess right away
        // what this value means in their browser.
        Cookie::queue('trump_is_a_dick', $request->get('secret'), 100000);

        return redirect('/home');
    }
}
