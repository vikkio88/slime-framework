<?php


namespace Tests\Helpers\Stubs;


use App\Lib\Slime\RestAction\ApiAction;

class ApiActionStub extends ApiAction
{

    protected function formatResponse()
    {
    }

    protected function performAction()
    {
        $this->payload = "settingSomeData";
    }
}