<?php


namespace App\Lib\Slime\RestAction;


use App\Lib\Helpers\Responder;
use App\Lib\Slime\Exceptions\SlimeException;
use App\Lib\Slime\RestAction\UseCase\Action;
use Slim\Http\Request;

abstract class ApiAction extends Action
{
    const OK_CODE = 200;
    const INTERNAL_SERVER_ERROR_CODE = 500;
    protected $code;
    protected $message;
    protected $payload;
    protected $pagination = null;

    protected function init()
    {
        $this->code = self::OK_CODE;
        $this->message = "OK";
        $this->payload = null;
    }

    protected function performChecks()
    {

    }

    protected function performCallBack()
    {

    }

    protected function formatResponse()
    {
        $bodyContent = $this->buildBody();

        $this->response = $this->response->withStatus($this->code);
        $this->response = Responder::getJsonResponse(
            $bodyContent,
            $this->response
        );
    }

    protected function manageSlimeException(SlimeException $slimeException)
    {
        $this->manageBaseException($slimeException);
        $this->payload = $slimeException->getPayload();
    }

    protected function manageBaseException(\Exception $baseException)
    {
        $this->code = self::INTERNAL_SERVER_ERROR_CODE;
        $this->message = $baseException->getMessage();
    }

    private function buildBody()
    {
        $body = [
            'code' => $this->code,
            'message' => $this->message,
            'payload' => $this->payload
        ];

        if (!empty($this->pagination)) {
            $body['pagination'] = $this->pagination;
        }
        return json_encode($body);
    }

    protected function getJsonRequestBody()
    {
        $json = $this->request->getBody();
        return json_decode($json, true);
    }

    protected function getQueryParams()
    {
        if ($this->request instanceof Request) {
            return $this->request->getQueryParams();
        }

        return [];
    }
}