<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Account\Account::class, function (Faker $faker) {
    return [
    ];
});

$factory->define(App\Models\Account\AuditLog::class, function (Faker $faker) {
    return [
        'account_id' => factory(App\Models\Account\Account::class)->create()->id,
        'action' => 'account_created',
        'objects' => '{"user": 1}',
    ];
});
