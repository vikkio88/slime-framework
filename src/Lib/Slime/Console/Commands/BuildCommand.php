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
        $this->refreshDistDir();
        $this->copyProjectStructure();
        $this->runInstall();
        $this->runVendorClean();
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
        array_map('unlink', glob("$folderName/*.*"));
        rmdir($folderName);

    }

    private function copyProjectStructure()
    {
        foreach ($this->mainFiles as $file) {
            copy($file, self::DIST_FOLDER);
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

}