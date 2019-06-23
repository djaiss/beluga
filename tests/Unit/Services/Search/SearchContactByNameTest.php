<?php

namespace Tests\Unit\Services\Contact\Contact;

use Tests\TestCase;
use App\Models\User\User;
use App\Models\Contact\Contact;
use App\Helpers\EncryptionHelper;
use App\Services\Search\SearchContactByName;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SearchContactByNameTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_searches_all_the_contacts_by_name()
    {
        $user = factory(User::class)->create([]);
        factory(Contact::class)->create([
            'account_id' => $user->account_id,
            'enc_first_name' => EncryptionHelper::encrypt('Dwight', $this->getSecretKey()),
        ]);
        factory(Contact::class)->create([
            'account_id' => $user->account_id,
            'enc_first_name' => EncryptionHelper::encrypt('Angela', $this->getSecretKey()),
        ]);
        factory(Contact::class)->create([
            'account_id' => $user->account_id,
            'enc_first_name' => EncryptionHelper::encrypt('Jim', $this->getSecretKey()),
        ]);

        $request = [
            'secret_key' => $this->getSecretKey(),
            'account_id' => $user->account_id,
            'terms' => 'Dwight',
        ];

        $contacts = (new SearchContactByName)->execute($request);

        $this->assertEquals(
            1,
            $contacts->count()
        );

        $request = [
            'secret_key' => $this->getSecretKey(),
            'account_id' => $user->account_id,
            'terms' => 'i',
        ];

        $contacts = (new SearchContactByName)->execute($request);

        $this->assertEquals(
            2,
            $contacts->count()
        );

        $request = [
            'secret_key' => $this->getSecretKey(),
            'account_id' => $user->account_id,
            'terms' => 'Michael',
        ];

        $contacts = (new SearchContactByName)->execute($request);

        $this->assertEquals(
            0,
            $contacts->count()
        );
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given()
    {
        $user = factory(User::class)->create([]);

        $request = [
            'secret_key' => $this->getSecretKey(),
            'account_id' => $user->account_id,
        ];

        $this->expectException(ValidationException::class);
        (new SearchContactByName)->execute($request);
    }
}
