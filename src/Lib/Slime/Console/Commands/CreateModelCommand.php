<?php


namespace App\Lib\Slime\Console\Commands;


use App\Lib\Helpers\TextFormatter;
use App\Lib\Slime\Console\NamespacedGeneratorHelperCommand;

class CreateModelCommand extends NamespacedGeneratorHelperCommand
{
    protected function getHead()
    {
        $fileHead = parent::getHead();
        $fileHead .= PHP_EOL . 'namespace App\Models;' . PHP_EOL . 'use App\Lib\Slime\Models\SlimeModel;' . PHP_EOL;
        return $fileHead;
    }

    protected function getFilePath()
    {
        return 'Models/';
    }

    protected function getStub()
    {
        return PHP_EOL . 'class ' .
        TextFormatter::snakeToCamelCase($this->getArg(0)) .
        ' extends SlimeModel {' . PHP_EOL . '}';
    }
}
