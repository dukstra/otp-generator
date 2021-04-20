<?php

namespace Dukstra\Otp\Tests;

use Dukstra\Otp\Facades\Otp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;


class OtpGeneratorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_able_to_generate_and_validate_the_otp()
    {
        $identifier = Str::random(12);
        $otp = Otp::generate($identifier);
        $validator = Otp::validateUsingIdentifier($identifier, $otp->token);
        $this->assertEquals(true, $validator->status);
    }

    /** @test */
    public function cant_able_to_verify_the_opt_once_get_expired()
    {
        $identifier = Str::random(12);
        $otp = Otp::generate($identifier);
        $this->travel(config('otp-generator.validity') + 1)->minutes();
        $validator = Otp::validateUsingIdentifier($identifier, $otp->token);
        $this->assertEquals(false, $validator->status);
    }

    /** @test */
    public function can_able_to_regenerate_and_validate_the_otp()
    {
        $identifier = Str::random(12);
        Otp::generate($identifier);
        $secondTime = Otp::generate($identifier);
        $validator = Otp::validateUsingIdentifier($identifier, $secondTime->token);
        $this->assertEquals(true, $validator->status);
        $this->assertDatabaseCount('otps', 1);
    }

    /** @test */
    public function cant_able_to_generate_the_otp_more_than_the_maximum_specified_time()
    {
        $identifier = Str::random(12);
        $limit = config('otp-generator.maximumOtpsAllowed');

        for ($i = 0; $i < $limit ; $i++) {
            Otp::generate($identifier);
        }
        $otp = Otp::generate($identifier);

        $this->assertEquals(false, $otp->status);
    }

    /** @test */
    public function the_otps_will_be_deleted_after_specified_amount_of_time()
    {
        $identifier = Str::random(12);
        $otp = Otp::generate($identifier);
        $this->travel(config('otp-generator.deleteOldOtps'))->minutes();
        $validator = Otp::validateUsingIdentifier($identifier, $otp->token);
        $this->assertEquals(false, $validator->status);
        $this->assertDatabaseCount('otps', 1);
        Otp::generate(Str::random(13));
        $this->assertDatabaseCount('otps', 1);
        $this->travelBack();
    }

    /** @test */
    public function cant_able_to_verify_the_otp_once_reach_the_maximum_allowedAttempts()
    {
        $identifier = Str::random(12);
        $otp = Otp::generate($identifier);
        $allowedAttempts = config('otp-generator.allowedAttempts');
        for ($i = 0; $i < $allowedAttempts ; $i++) {
            Otp::validateUsingIdentifier($identifier, 'wrongToken');
        }
        $validator = Otp::validateUsingIdentifier($identifier, $otp->token);
        $this->assertEquals(false, $validator->status);
    }

    /** @test */
    public function can_able_set_custom_validity_time_and_maximum_otps_allowed_numbers()
    {
        $identifier = Str::random(12);
        Otp::setValidity(30)
            ->generate($identifier);

        $this->assertDatabaseHas('otps', [
            'validity' => 30,
        ]);
        $identifier = Str::random(11);
        $maximumOtpsAllowed = 10;
        for ($i = 0; $i < $maximumOtpsAllowed  ; $i++) {
            $otp = Otp::setMaximumOtpsAllowed($maximumOtpsAllowed)
                ->generate($identifier);
            $this->assertEquals(true, $otp->status);
        }
    }

    /** @test */
    public function can_able_set_custom_number_of_allowed_attempts()
    {
        $identifier = Str::random(12);
        $otp = Otp::generate($identifier);
        $allowedAttempts = 10;
        for ($i = 0; $i < $allowedAttempts - 1 ; $i++) {
            Otp::setAllowedAttempts($allowedAttempts)
                ->validateUsingIdentifier($identifier, 'wrongToken');
        }
        $validator = Otp::validateUsingIdentifier($identifier, $otp->token);
        $this->assertEquals(true, $validator->status);
    }

    /** @test */
    public function can_able_to_set_custom_otp_length()
    {
        $identifier = Str::random(12);
        $otp = Otp::setLength(8)
            ->generate($identifier);
        $this->assertEquals(8, strlen($otp->token));
    }

    /** @test */
    public function can_able_get_same_token_on_second_time_onwards()
    {
        $identifier = Str::random(12);
        $otp1 = Otp::generate($identifier);
        $otp2 = Otp::setUseSameToken(true)->generate($identifier);

        $this->assertEquals($otp1->token, $otp2->token);
    }

    /** @test */
    public function can_able_to_get_expired_at_time()
    {
        $identifier = Str::random(12);
        Otp::generate($identifier);
        $expires = Otp::expiredAt($identifier);
        $this->assertEquals(9, $expires->expired_at->diffInMinutes());
    }
}
