<?php

namespace Tests\Unit\Helpers;

use Tests\TestCase;
use App\Helpers\EncryptionHelper;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EncryptionHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_encrypts_a_string()
    {
        $string = 'test';
        $key = '123456789ABCDEFGHIJKLMNOPQRSTUVW';

        $encrypted = EncryptionHelper::encrypt($string, $key);

        $this->assertIsString($encrypted);
    }

    /** @test */
    public function it_decrypts_a_string()
    {
        $encrypted = 'eyJpdiI6InRoQnNtXC9cL1wvRVlPakpia3dZR0VYa3c9PSIsInZhbHVlIjoiVjcrQUZ5R1g4c3FhQzFkWEpvc29vdz09IiwibWFjIjoiNmQzNjAzNDZkMTcyNTU2ZDQ0NTU3NTM4MDk1ZTRiY2ZjOGIxNjY0ZTFiODY3OTVmMjY3NGEyYzlhNjlhODZiMSJ9';
        $key = '123456789ABCDEFGHIJKLMNOPQRSTUVW';

        $decrypted = EncryptionHelper::decrypt($encrypted, $key);

        $this->assertEquals(
            'test',
            $decrypted
        );
    }
}
