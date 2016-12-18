<?php


namespace App\Lib\Slime\Console;


use App\Lib\Helpers\TextFormatter;

abstract class NamespacedGeneratorHelperCommand extends GeneratorHelperCommand
{

    protected $filenameRoot = null;
    protected $namespaceStruct = [];

    protected function getFullFileName()
    {
        $filePath = $this->getFilePath();
        $filePath .= $this->directoriesFromNameSpace();
        $this->createDirectoriesFromNamespace();
        return $filePath . $this->getFileName() . $this->getFileExtension();
    }

    protected function getFileName()
    {
        return TextFormatter::snakeToCamelCase(
            $this->filenameRoot
        );
    }

    private function directoriesFromNameSpace()
    {
        if (empty($this->namespaceStruct)) {
            $fullParam = $this->getArg(0);
            $this->namespaceStruct = explode('\\', $fullParam);
            $this->filenameRoot = array_pop($this->namespaceStruct);
            $this->namespaceStruct = array_map(
                function ($element) {
                    return TextFormatter::snakeToCamelCase($element);
                },
                $this->namespaceStruct
            );
        }
        return implode(DIRECTORY_SEPARATOR, $this->namespaceStruct) . DIRECTORY_SEPARATOR;
    }

    private function createDirectoriesFromNamespace()
    {
        $startingPath = $this->getFilePath();
        $incremental = null;
        foreach ($this->namespaceStruct as $subDir) {
            $current = '';
            if ($incremental !== null) {
                $current .= $incremental . DIRECTORY_SEPARATOR;
            }
            $current .= $subDir;

            if (!$this->checkDir($startingPath . $current)) {
                mkdir($startingPath . $current);
            }
            $incremental = $current;
        }
    }

}