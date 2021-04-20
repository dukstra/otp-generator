<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Generated OPT Validity time by minutes
    |--------------------------------------------------------------------------
    |
    | By default = 10 minutes
    |
    */
    'validity' => env('OTP_VALIDITY_TIME', 10),
    /*
    |--------------------------------------------------------------------------
    |  Length of the generated OTP
    |--------------------------------------------------------------------------
    |
    | By default = 6 digits used
    |
    */
    'length' => env('OTP_LENGTH', 6),

    /*
    |--------------------------------------------------------------------------
    | Allowed attempts within duration of attempts_count_time
    |--------------------------------------------------------------------------
    |
    | This filed will be used when validating the generated OTP token.
    |
    */
    'allowedAttempts' => env('OTP_ALLOWED_ATTEMPTS', 5),


    /*
    |--------------------------------------------------------------------------
    | Generated OTP Type
    |-------------------------------------------------------------------------
    |
    | if true the generated OTP contains only digits. ex : 654321
    | if false the generated OTP contains only alphanumeric. ex : 21ab43
    */
    'onlyDigits' => true,

    /*
    |--------------------------------------------------------------------------
    | Use same token to resend the OTP
    |-------------------------------------------------------------------------
    |
    | if true the the second time onwards generated OTPs same a the first one (Only OTP validation time)
    | if false each time unique OPT will be generated
    */
    'useSameToken' => false,

    /*
    |--------------------------------------------------------------------------
    | Delete old otps older than specified minutes
    |-------------------------------------------------------------------------
    |
    | Default 30 minutes.
    */
    'deleteOldOtps' => 30,

    /*
    |--------------------------------------------------------------------------
    |  maximum OTPs allowed being generated during the deleteOldOtps time.
    |--------------------------------------------------------------------------
    |
    | Once the limit is reached, the end-user can't able to generate OPT until the OTP deleteOldOtps time is over.
    |
    */
    'maximumOtpsAllowed' => env('MAXIMUM_OTPS_ALLOWED', 5),
];
