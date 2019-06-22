<?php

namespace Tests\Unit\Services\Contact\Contact;

use Tests\TestCase;
use App\Models\User\User;
use App\Models\Contact\Contact;
use Illuminate\Validation\ValidationException;
use App\Services\Contact\Contact\CreateContact;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateContactTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_contact()
    {
        $user = factory(User::class)->create([]);

        $request = [
            'secret' => $this->getSecretKey(),
            'account_id' => $user->account_id,
            'author_id' => $user->id,
            'first_name' => 'Dwight',
        ];

        $contact = (new CreateContact)->execute($request);

        $this->assertDatabaseHas('contacts', [
            'id' => $contact->id,
            'account_id' => $user->account_id,
        ]);

        $this->assertInstanceOf(
            Contact::class,
            $contact
        );

        $this->assertDatabaseHas('audit_logs', [
            'account_id' => $user->account_id,
            'action' => 'contact_created',
        ]);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given()
    {
        $user = factory(User::class)->create([]);

        $request = [
            'account_id' => $user->account_id,
            'author_id' => $user->id,
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new CreateContact)->execute($request);
    }
}
