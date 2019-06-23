<?php

namespace App\Http\Controllers\Web\Home;

use Illuminate\Http\Request;
use App\Helpers\EncryptionHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cookie;
use App\Http\Resources\User\User as UserResource;
use App\Http\Resources\Contact\Contact as ContactResource;

class HomeController extends Controller
{
    /**
     * Display the user home page.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $contacts = auth()->user()->account->contacts()->get();

        $contacts = EncryptionHelper::removeEncryptionForClient(
            $contacts,
            Cookie::get('trump_is_a_dick')
        );

        return View::component('Home', [
            'user' => new UserResource(auth()->user()),
            'contacts' => ContactResource::collection($contacts),
        ]);
    }
}
