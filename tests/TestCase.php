<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Get a generic secret key to trigger the encryption.
     *
     * @return string
     */
    public function getSecretKey() : string
    {
        return '1234567890ABCDEFGHIJKLMNOPQRSTUV';
    }
}
