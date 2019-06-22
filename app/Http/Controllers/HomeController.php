<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;
use App\Http\Resources\User\User as UserResource;

class HomeController extends Controller
{
    /**
     * Display the user home page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::component('Home', [
            'user' => new UserResource(auth()->user()),
        ]);
    }
}
