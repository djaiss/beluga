<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class EncryptionHelper
{
    /**
     * Encrypt a string.
     *
     * @param string $string
     * @param string $key
     * @return string
     */
    public static function encrypt($string, $key) : string
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
    public static function decrypt($string, $key) : string
    {
        $newEncrypter = new \Illuminate\Encryption\Encrypter($key, 'AES-256-CBC');
        return $newEncrypter->decrypt($string);
    }

    /**
     * Remove encryption for an object or a collection, thanks to the
     * secret key given in parameters.
     *
     * @param mixed $object
     * @param string $secret
     * @return mixed
     */
    public static function removeEncryptionForClient($object, string $secret)
    {
        if ($object instanceof Collection) {
            foreach ($object as $singleObject) {
                $singleObject = self::makeObjectReadableByHuman($singleObject, $secret);
            }
        } else {
            $object = self::makeObjectReadableByHuman($object, $secret);
        }

        return $object;
    }

    /**
     * Read an object and decrypt fields started with `enc_`, which means they
     * were encrypted in the first place.
     *
     * @param mixed $object
     * @param string $secret
     * @return mixed
     */
    private static function makeObjectReadableByHuman($object, string $secret)
    {
        foreach ($object->toArray() as $key => $value) {
            if (Str::startsWith($key, 'enc_')) {
                $value = self::decrypt($value, $secret);
                $object[$key] = $value;
            }
        }

        return $object;
    }
}
