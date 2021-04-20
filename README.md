# OTP Generator and Validator for Laravel Applications

## Installation

You can install the package via composer:

```bash
composer require dukstra/otp
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="Dukstra\Otp\Providers\OtpServiceProvider" --tag="migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --provider="Dukstra\Otp\Providers\OtpServiceProvider" --tag="config"
```

## Usage

```php
use Dukstra\Otp\Otp;
.
.
$otp =  Otp::generate($identifier);
.
$verify = Otp::validateUsingIdentifier($identifier, $otp->token);
// example response
{
  "status": true
  "message": "OTP is valid"
}

// to get an expiredAt time
$expires = Otp::expiredAt($identifier);

// example response 
{
+"status": true
+"expired_at": Illuminate\Support\Carbon @1611895244^ {
  ....
  #dumpLocale: null
  date: 2021-01-29 04:40:44.0 UTC (+00:00)
}

```

You have control to update the setting at otp-generator.php config file, but you can also control while generating.

## Advance Usage

```php
use Dukstra\Otp\Otp;
.
.
$otp =  Otp::setValidity(30)  // otp validity time in mins
      ->setLength(4)  // Length of the generated otp
      ->setMaximumOtpsAllowed(10) // Number of times allowed to regenerate otps
      ->setOnlyDigits(false)  // generated otp contains mixed characters ex:ad2312
      ->setUseSameToken(true) // if you re-generate OTP, you will get same token
      ->generate($identifier);
.
$verify = Otp::setAllowedAttempts(10) // number of times they can allow to attempt with wrong token
    ->validateUsingIdentifier($identifier, $otp->token);

```

## Testing

    composer test

## Credits

-   [sesha](https://github.com/seshac)

## License

The MIT License (MIT).
