<?php

namespace App\Http\Controllers\Web\Search;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use App\Services\Search\SearchContactByName;
use App\Http\Resources\Contact\Contact as ContactResource;

class ContactNameSearchController extends Controller
{
    /**
     * Search a contact by name.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $terms = $request->get('terms');

        $contacts = (new SearchContactByName)->execute([
            'secret_key' => Cookie::get('trump_is_a_dick'),
            'account_id' => auth()->user()->account_id,
            'terms' => $terms,
        ]);

        return ContactResource::collection($contacts);
    }
}
