<?php


namespace App\Lib\Slime\Console\Commands;


use App\Lib\Slime\Console\GeneratorHelperCommand;

class CreateRouteCommand extends GeneratorHelperCommand
{
    protected function getFilePath()
    {
        return 'routes/' . $this->getArg(0) . '/';
    }

    protected function getFileName()
    {
        return 'routes';
    }

    protected function getStub()
    {
        return PHP_EOL .
        '$api->get(\'/'.$this->getArg(0).'\', SOMEACTION::class);'
        . PHP_EOL;
    }


}