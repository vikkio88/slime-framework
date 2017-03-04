<?php


namespace App\Lib\Slime\Console\Commands;


use App\Lib\Helpers\Config;
use App\Lib\Slime\Console\SlimeCommand;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class BuildCommand extends SlimeCommand
{
    const DIST_FOLDER = './dist/';
    const COMPOSER_INSTALL = 'composer install --no-ansi --no-dev --no-interaction --no-progress --no-scripts --optimize-autoloader';
    const DIST_VENDOR = './dist/vendor/';
    protected $projectFolders = [
        'Actions',
        'config',
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

    protected $vendorFolderToRemove = [
        '.git',
        'example',
        'examples',
        'tests',
        'test',
        'Test',
        'Tests',
        'docs',
        'tools',
        'doc',
        'ext',
        '.github'
    ];

    protected $vendorFilesToRemove = [
        '.gitignore',
        '.gitattributes',
        '.editorconfig',
        'CHANGELOG',
        '.travis.yml',
        'README.md',
        'readme.md',
        'README.rst',
        'CONTRIBUTE.md',
        'CONTRIBUTING.md',
        'LICENSE.md',
        'phpcs.xml',
        'phpunit.xml',
        'phpunit.xml.dist',
        '.php_cs',
        '.php_cs.dist',
        'composer.json',
        '.env',
        '.htaccess',
        'index.php',
        'test.php'
    ];

    public function __construct(array $args)
    {
        parent::__construct($args);
        $this->mergeConfigs();
    }

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
        $this->logInfo('Cleaning vendor/');
        $this->cleanVendor();
        $this->logInfo("Done, don't forget to check the .env file");
    }

    private function refreshDistDir()
    {
        if (is_dir(self::DIST_FOLDER)) {
            $this->rmrdir(self::DIST_FOLDER);
        }
        mkdir(self::DIST_FOLDER);
        if ($this->getArg(0) === 'clean') {
            echo "dist/ folder cleaned up" . PHP_EOL;
            exit(0);
        }
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

    private function logInfo($message)
    {
        if (!$this->hasArg('-v') || !$this->hasArg('--verbose')) {
            return;
        }

        echo (new \DateTime())->format('Y-m-d H:i:s') . ' --- ' . $message . PHP_EOL;
    }

    private function cleanVendor()
    {
        $allVendors = glob(self::DIST_VENDOR . '*', GLOB_ONLYDIR);
        foreach ($allVendors as $vendor) {
            $packages = glob($vendor . '/*', GLOB_ONLYDIR);
            foreach ($packages as $package) {
                foreach ($this->vendorFilesToRemove as $file) {
                    $fullPath = $package . '/' . $file;
                    if (file_exists($fullPath)) {
                        $this->logInfo('Removing file: ' . $fullPath);
                        unlink($fullPath);
                    }
                }

                foreach ($this->vendorFolderToRemove as $folder) {
                    $fullPath = $package . '/' . $folder;
                    if (is_dir($fullPath)) {
                        $this->logInfo('Removing dir: ' . $fullPath);
                        exec('rm -rf ' . $fullPath);
                    }
                }
            }
        }
    }

    private function mergeConfigs()
    {
        $this->projectFolders = $this->mergeConfig($this->projectFolders, 'build.folders');
        $this->mainFiles = $this->mergeConfig($this->mainFiles, 'build.files');
        $this->vendorFolderToRemove = $this->mergeConfig($this->vendorFolderToRemove, 'build.vendorFolders');
        $this->vendorFilesToRemove = $this->mergeConfig($this->vendorFilesToRemove, 'build.vendorFiles');
    }

    private function mergeConfig($localConfig, $configKey)
    {
        return array_merge(
            $localConfig,
            Config::get($configKey)
        );
    }

}