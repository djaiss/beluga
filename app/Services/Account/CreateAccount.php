<?php

namespace App\Services\Account;

use App\Models\User\User;
use Illuminate\Support\Str;
use App\Mail\ConfirmAccount;
use App\Services\BaseService;
use App\Models\Account\Account;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class CreateAccount extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|unique:users,email|email|max:255|not_throw_away',
            'password' => 'required|alpha_dash|string|max:255',
        ];
    }

    /**
     * Create a user, the assocatied account and send a confirmation email.
     *
     * @param array $data
     * @return User
     */
    public function execute(array $data) : User
    {
        $this->validate($data);

        $user = $this->createUser($data);

        $account = $this->createAccount($user);

        $user->account_id = $account->id;
        $user->save();

        (new LogAuditAction)->execute([
            'account_id' => $user->account_id,
            'action' => 'account_created',
            'objects' => json_encode([
                'user_id' => $user->id,
            ]),
        ]);

        return $user;
    }

    /**
     * Create the user.
     *
     * @param array $data
     * @return User
     */
    private function createUser(array $data) : User
    {
        $uuid = Str::uuid()->toString();

        $user = User::create([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'uuid' => $uuid,
        ]);

        $user = $this->generateConfirmationLink($user);

        $this->scheduleConfirmationEmail($user);

        return $user;
    }

    /**
     * Generate a confirmation link for the user.
     *
     * @param User $user
     * @return User
     */
    private function generateConfirmationLink($user) : User
    {
        $user->verification_link = Str::uuid()->toString();
        $user->save();

        return $user;
    }

    /**
     * Schedule a confirmation email to be sent.
     *
     * @param User $user
     * @return void
     */
    private function scheduleConfirmationEmail(User $user)
    {
        Mail::to($user->email)
            ->queue(new ConfirmAccount($user));
    }

    /**
     * Create an account.
     *
     * @param User $user
     * @return Account
     */
    private function createAccount(User $user) : Account
    {
        return Account::create([
        ]);
    }
}
