<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Contact\Contact::class, function (Faker $faker) {
    return [
        'account_id' => factory(App\Models\Account\Account::class)->create()->id,
        'enc_first_name' => 'Dwight',
        'uuid' => $faker->uuid,
        'avatar' => 'https://api.adorable.io/avatars/285/abott@adorable.png',
    ];
});
