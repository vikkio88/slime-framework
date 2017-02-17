<?php


namespace App\Lib\Slime\Console\Commands;


use App\Lib\Slime\Console\SlimeCommand;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class BuildCommand extends SlimeCommand
{
    const DIST_FOLDER = './dist/';
    const COMPOSER_INSTALL = 'composer install --no-ansi --no-dev --no-interaction --no-progress --no-scripts --optimize-autoloader';
    const VENDOR_CLEAN = 'php vendor/mediamonks/composer-vendor-cleaner/bin/clean';
    protected $projectFolders = [
        'Actions',
        'config',
        'database',
        'Lib',
        'Models',
        'routes',
    ];
    protected $mainFiles = [
        'composer.json',
        '.env',
        '.htaccess',
        'index.php'
    ];

    /**
     * @return int
     */
    public function run()
    {
        $this->logInfo('Cleaning dist Folder');
        $this->refreshDistDir();
        $this->logInfo('Copying Project files');
        $this->copyProjectStructure();
        $this->logInfo('Running composer');
        $this->runInstall();
        $this->logInfo('Cleaning-up vendor/');
        $this->runVendorClean();
        $this->logInfo("Done, don't forget to check the .env file");
    }

    private function refreshDistDir()
    {
        if (is_dir(self::DIST_FOLDER)) {
            $this->rmrdir(self::DIST_FOLDER);
        }
        mkdir(self::DIST_FOLDER);
    }

    private function rmrdir($folderName)
    {
        exec('rm -rf ' . $folderName);
    }

    private function copyProjectStructure()
    {
        foreach ($this->mainFiles as $file) {
            copy($file, self::DIST_FOLDER . $file);
        }

        foreach ($this->projectFolders as $folder) {
            $this->dircopy($folder, self::DIST_FOLDER . $folder);
        }
    }

    private function dircopy($target, $destination)
    {
        mkdir($destination, 0755);
        foreach (
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($target, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::SELF_FIRST) as $item
        ) {
            if ($item->isDir()) {
                mkdir($destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
            } else {
                copy($item, $destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
            }
        }
    }

    private function runInstall()
    {
        exec('cd ' . self::DIST_FOLDER . ' && ' . self::COMPOSER_INSTALL);
        exec('cd ..');
    }

    private function runVendorClean()
    {
        exec('cd ' . self::DIST_FOLDER . ' && ' . self::VENDOR_CLEAN);
    }

    private function logInfo($message)
    {
        if ($this->getArg(0) !== '-v') {
            return;
        }

        echo (new \DateTime())->format('Y-m-d H:i:s') . ' --- ' . $message . PHP_EOL;
    }

}