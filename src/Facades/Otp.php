<?php

namespace Dukstra\Otp\Facades;

use Illuminate\Support\Facades\Facade;

/**
* @method static object generate(string $identifier)
* @method static object validateUsingIdentifier(string $identifier, string $token)
* @method static object validateUsingId(int $id, string $token)
* @method static object expiredAt(string $identifier)
* @method static object setLength(int $value)
* @method static object setOnlyDigits(bool $value)
* @method static object setMaximumOtpsAllowed(int $value)
* @method static object setUseSameToken(int $value)
* @method static object setValidity(int $value)
* @method static object setAllowedAttempts(int $value)
**/

class Otp extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'otp-generator';
    }
}
