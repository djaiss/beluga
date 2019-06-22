<?php

namespace App\Helpers;

class EncryptionHelper
{
    /**
     * Encrypt a string.
     *
     * @param string $string
     * @param string $key
     * @return string
     */
    public static function encrypt($string, $key): string
    {
        $newEncrypter = new \Illuminate\Encryption\Encrypter($key, 'AES-256-CBC');
        return $newEncrypter->encrypt($string);
    }

    /**
     * Decrypt a string.
     *
     * @param string $string
     * @param string $key
     * @return string
     */
    public static function decrypt($string, $key): string
    {
        $newEncrypter = new \Illuminate\Encryption\Encrypter($key, 'AES-256-CBC');
        return $newEncrypter->decrypt($string);
    }
}
