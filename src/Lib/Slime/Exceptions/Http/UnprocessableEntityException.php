<?php


namespace App\Lib\Slime\Exceptions\Http;


use App\Lib\Slime\Exceptions\SlimeException;

class UnprocessableEntityException extends SlimeException
{
    protected $code = 422;
    protected $errorMessage = 'Unprocessable entity';

    public function __construct(array $errors)
    {
        parent::__construct($this->message);
        $this->payload = $errors;
    }

}