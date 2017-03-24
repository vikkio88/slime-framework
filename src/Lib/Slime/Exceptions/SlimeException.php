<?php


namespace App\Lib\Slime\Exceptions;

use Exception;

class SlimeException extends \Exception
{
    protected $code = 500;
    protected $message = "Ops!";
    protected $payload = null;

    public function __construct($message = null)
    {
        $message = $message ?: $this->message;
        parent::__construct($message, $this->code);
    }

    public function getPayload()
    {
        return $this->payload;
    }

}