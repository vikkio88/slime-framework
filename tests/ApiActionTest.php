<?php


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Tests\Helpers\Stubs\ApiActionExceptionStub;
use Tests\Helpers\Stubs\ApiActionStub;
use Tests\Helpers\Stubs\ResponseStub;

class ApiActionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group App
     * @group ApiAction
     */
    public function testThatSettingSomeDataItWillGiveItBack()
    {
        $apiAction = new ApiActionStub();
        $apiAction->__invoke(
            $this->getMock(RequestInterface::class),
            new ResponseStub(),
            []
        );

        $response = $apiAction->execute();
        $this->assertNotEmpty($response);
    }

    /**
     * @group App
     * @group ApiAction
     * @group ApiActionException
     */
    public function testThatThrowingAnExceptionWillBeCatchAndFormatted()
    {
        $apiAction = new ApiActionExceptionStub();
        $apiAction->__invoke(
            $this->getMock(RequestInterface::class),
            new ResponseStub(),
            []
        );
        $response = $apiAction->execute();
        $this->assertNotEmpty($response);
    }
}
