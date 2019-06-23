<?php

namespace Tests\Unit\Helpers;

use Tests\TestCase;
use App\Models\Account\Account;
use App\Models\Contact\Contact;
use App\Helpers\EncryptionHelper;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EncryptionHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_encrypts_a_string()
    {
        $string = 'test';
        $secret = '123456789ABCDEFGHIJKLMNOPQRSTUVW';

        $encrypted = EncryptionHelper::encrypt($string, $secret);

        $this->assertIsString($encrypted);
    }

    /** @test */
    public function it_decrypts_a_string()
    {
        $encrypted = 'eyJpdiI6InRoQnNtXC9cL1wvRVlPakpia3dZR0VYa3c9PSIsInZhbHVlIjoiVjcrQUZ5R1g4c3FhQzFkWEpvc29vdz09IiwibWFjIjoiNmQzNjAzNDZkMTcyNTU2ZDQ0NTU3NTM4MDk1ZTRiY2ZjOGIxNjY0ZTFiODY3OTVmMjY3NGEyYzlhNjlhODZiMSJ9';
        $secret = '123456789ABCDEFGHIJKLMNOPQRSTUVW';

        $decrypted = EncryptionHelper::decrypt($encrypted, $secret);

        $this->assertEquals(
            'test',
            $decrypted
        );
    }

    /** @test */
    public function it_removes_the_encryption_from_an_object()
    {
        $encrypted = 'eyJpdiI6InRoQnNtXC9cL1wvRVlPakpia3dZR0VYa3c9PSIsInZhbHVlIjoiVjcrQUZ5R1g4c3FhQzFkWEpvc29vdz09IiwibWFjIjoiNmQzNjAzNDZkMTcyNTU2ZDQ0NTU3NTM4MDk1ZTRiY2ZjOGIxNjY0ZTFiODY3OTVmMjY3NGEyYzlhNjlhODZiMSJ9';
        $secret = '123456789ABCDEFGHIJKLMNOPQRSTUVW';

        $contact = factory(Contact::class)->create([
            'enc_first_name' => $encrypted,
        ]);

        $this->assertEquals(
            'eyJpdiI6InRoQnNtXC9cL1wvRVlPakpia3dZR0VYa3c9PSIsInZhbHVlIjoiVjcrQUZ5R1g4c3FhQzFkWEpvc29vdz09IiwibWFjIjoiNmQzNjAzNDZkMTcyNTU2ZDQ0NTU3NTM4MDk1ZTRiY2ZjOGIxNjY0ZTFiODY3OTVmMjY3NGEyYzlhNjlhODZiMSJ9',
            $contact->enc_first_name
        );

        $contact = EncryptionHelper::removeEncryptionForClient($contact, $secret);

        $this->assertEquals(
            'test',
            $contact->enc_first_name
        );
    }

    /** @test */
    public function it_removes_the_encryption_from_a_collection()
    {
        $encrypted = 'eyJpdiI6InRoQnNtXC9cL1wvRVlPakpia3dZR0VYa3c9PSIsInZhbHVlIjoiVjcrQUZ5R1g4c3FhQzFkWEpvc29vdz09IiwibWFjIjoiNmQzNjAzNDZkMTcyNTU2ZDQ0NTU3NTM4MDk1ZTRiY2ZjOGIxNjY0ZTFiODY3OTVmMjY3NGEyYzlhNjlhODZiMSJ9';
        $secret = '123456789ABCDEFGHIJKLMNOPQRSTUVW';

        $account = factory(Account::class)->create([]);
        factory(Contact::class)->create([
            'account_id' => $account->id,
            'enc_first_name' => $encrypted,
        ]);

        $contacts = $account->contacts()->get();

        $alteredContacts = EncryptionHelper::removeEncryptionForClient($contacts, $secret);

        foreach ($alteredContacts as $contact) {
            $this->assertEquals(
                'test',
                $contact->enc_first_name
            );
        }
    }
}
