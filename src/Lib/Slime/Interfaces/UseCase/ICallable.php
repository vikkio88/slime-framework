<?php


namespace App\Lib\Slime\Interfaces\UseCase;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface ICallable
{
    public function __invoke(RequestInterface $request, ResponseInterface $response, array $args);
}