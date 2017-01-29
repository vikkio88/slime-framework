<?php


use App\Lib\Helpers\JwtHelper;

class JwtHelperTest extends PHPUnit_Framework_TestCase
{

    /**
     * @group Helpers
     * @group JwtHelper
     */
    public function testDoesEncodeAndDecode()
    {
        $payload = ['some' => 'junk'];
        $encoded = JwtHelper::encode($payload);
        $this->assertNotEmpty($encoded);
        $this->assertEquals($payload, JwtHelper::decode($encoded));
    }

}
