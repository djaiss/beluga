<?php

namespace Tests\Unit\Models\User;

use Tests\TestCase;
use App\Models\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_an_account()
    {
        $user = factory(User::class)->create([]);
        $this->assertTrue($user->account()->exists());
    }

    // /** @test */
    // public function it_has_many_notifications()
    // {
    //     $user = factory(User::class)->create([]);
    //     factory(Notification::class, 3)->create([
    //         'user_id' => $user->id,
    //     ]);
    //     $this->assertTrue($user->notifications()->exists());
    // }

    /** @test */
    public function it_gets_the_path_for_the_confirmation_link()
    {
        $user = factory(User::class)->create([
            'verification_link' => 'dunder',
        ]);

        $this->assertEquals(
            config('app.url').'/register/confirm/dunder',
            $user->getPathConfirmationLink()
        );
    }
}
