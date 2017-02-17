<?php


namespace App\Lib\Slime\Console\Commands;


use App\Lib\Helpers\TextFormatter;
use App\Lib\Slime\Console\NamespacedGeneratorHelperCommand;

class CreateActionCommand extends NamespacedGeneratorHelperCommand
{
    protected function getHead()
    {
        $fileHead = parent::getHead();
        $fileHead .= PHP_EOL
            . 'namespace App\Actions'
            . $this->getNamespaceString()
            . ';' . PHP_EOL
            . 'use App\Lib\Slime\RestAction\ApiAction;'
            . PHP_EOL;
        return $fileHead;
    }

    protected function getFilePath()
    {
        return 'Actions/';
    }

    protected function getStub()
    {
        return PHP_EOL . 'class ' .
            TextFormatter::snakeToCamelCase($this->filenameRoot)
            . ' extends ApiAction {'
            . PHP_EOL
            . 'protected function performAction()'
            . PHP_EOL
            . '{' . PHP_EOL
            . '    $this->payload = null;'
            . PHP_EOL
            . '}' . PHP_EOL
            . '}';
    }
}
