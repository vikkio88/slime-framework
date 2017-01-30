<?php

use App\Lib\Helpers\Config;
use App\Lib\Helpers\TokenHelper;

class TokenHelperTest extends PHPUnit_Framework_TestCase
{

    /**
     * @group Helpers
     * @group TokenHelper
     */
    public function testTokenPayloadHasTheCorrectValues()
    {
        $mockedUserId = 1;
        $expectedKeys = ['userId', 'validUntil', 'createdAt'];
        $tokenPayload = TokenHelper::getTokenPayload($mockedUserId);
        $this->assertEquals($expectedKeys, array_keys($tokenPayload));
    }

    /**
     * @group Helpers
     * @group TokenHelper
     */
    public function testTokenPayloadHasTheCorrectTimeValidity()
    {
        $mockedUserId = 1;
        $validity = Config::get('app.tokenLife') * 3600;
        $tokenPayload = TokenHelper::getTokenPayload($mockedUserId);
        $this->assertEquals($tokenPayload['createdAt'] + $validity, $tokenPayload['validUntil']);
    }

    /**
     * @group Helpers
     * @group TokenHelper
     */
    public function testGeneratingANewRandomToken()
    {
        $token = TokenHelper::generateRandomToken();
        echo $token;
    }

}
