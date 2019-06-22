<?php

namespace App\Http\Controllers\Contact;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cookie;
use App\Services\Contact\Contact\CreateContact;
use App\Http\Resources\User\User as UserResource;
use App\Http\Resources\Contact\Contact as ContactResource;

class ContactController extends Controller
{
    /**
     * Show the Create contact view.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View::component('CreateContact', [
            'user' => new UserResource(auth()->user()),
        ]);
    }

    /**
     * Create the employee.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request = [
            'secret' => Cookie::get('trump_is_a_dick'),
            'account_id' => auth()->user()->account_id,
            'author_id' => auth()->user()->id,
            'first_name' => $request->get('first_name'),
        ];

        $contact = (new CreateContact)->execute($request);

        return new ContactResource($contact);
    }

    /**
     * Delete the employee.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $companyId, $employeeId)
    {
        $request = [
            'company_id' => $companyId,
            'employee_id' => $employeeId,
            'author_id' => auth()->user()->id,
        ];

        (new DestroyEmployee)->execute($request);

        return redirect(tenant('/account/employees'));
    }
}
