<?php

namespace Tests\Unit\Services\Account;

use Tests\TestCase;
use App\Models\Account\Account;
use Illuminate\Support\Facades\Hash;
use App\Services\Account\VerifySecretKey;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotTheRightSecretKeyException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class VerifySecretKeyTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_checks_that_the_secret_key_is_the_good_one()
    {
        $account = factory(Account::class)->create([
            'secret_key_hash' => Hash::make($this->getSecretKey()),
        ]);

        $request = [
            'account_id' => $account->id,
            'secret_key' => $this->getSecretKey(),
        ];

        $response = (new VerifySecretKey)->execute($request);

        $this->assertTrue($response);
    }

    /** @test */
    public function it_throws_an_exception_if_the_secret_key_is_not_the_right_one()
    {
        $account = factory(Account::class)->create([
            'secret_key_hash' => Hash::make($this->getSecretKey()),
        ]);

        $request = [
            'account_id' => $account->id,
            'secret_key' => 'wrong password that is wrong bec',
        ];

        $this->expectException(NotTheRightSecretKeyException::class);
        (new VerifySecretKey)->execute($request);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given()
    {
        $request = [
            'secret_key' => 'dwight@dundermifflin.com',
        ];

        $this->expectException(ValidationException::class);
        (new VerifySecretKey)->execute($request);
    }
}
