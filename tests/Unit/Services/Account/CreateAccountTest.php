<?php

namespace Tests\Unit\Services\Account;

use Tests\TestCase;
use App\Models\User\User;
use App\Mail\ConfirmAccount;
use Illuminate\Support\Facades\Mail;
use App\Services\Account\CreateAccount;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateAccountTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_user_and_an_account()
    {
        $request = [
            'email' => 'dwight@dundermifflin.com',
            'password' => 'password',
        ];

        $createdUser = (new CreateAccount)->execute($request);

        $this->assertDatabaseHas('users', [
            'id' => $createdUser->id,
            'account_id' => $createdUser->account_id,
            'email' => 'dwight@dundermifflin.com',
        ]);

        $this->assertDatabaseHas('accounts', [
            'id' => $createdUser->account->id,
        ]);

        $this->assertInstanceOf(
            User::class,
            $createdUser
        );

        $this->assertDatabaseHas('audit_logs', [
            'account_id' => $createdUser->account_id,
            'action' => 'account_created',
        ]);
    }

    /** @test */
    public function it_generates_a_confirmation_link()
    {
        $request = [
            'email' => 'dwight@dundermifflin.com',
            'password' => 'password',
        ];

        $user = (new CreateAccount)->execute($request);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email_verified_at' => null,
        ]);

        $this->assertNotNull($user->verification_link);
    }

    /** @test */
    public function it_schedules_an_email()
    {
        $request = [
            'email' => 'dwight@dundermifflin.com',
            'password' => 'password',
        ];

        Mail::fake();

        $user = (new CreateAccount)->execute($request);

        Mail::assertQueued(ConfirmAccount::class, function ($mail) use ($user) {
            return $mail->user->id === $user->id;
        });
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given()
    {
        $request = [
            'email' => 'dwight@dundermifflin.com',
        ];

        $this->expectException(ValidationException::class);
        (new CreateAccount)->execute($request);
    }
}
