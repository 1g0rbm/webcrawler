<?php

namespace Ig0rbm\Webcrawler;

use Symfony\Component\Console\Application as Console;
use Symfony\Component\Dotenv\Dotenv;

/**
 * @package Ig0rbm\Webcrawler
 * @author 1g0rbm <m1g0rb89@gmail.com>
 */
class CrawlerKernel
{
    /**
     * @var Console
     */
    private $console;

    private $projectDir;

    public function __construct()
    {
        $this->projectDir = $this->getProjectDir();
        $this->consoleInitialize();

        $this->laodDotenv(new Dotenv());
    }

    public function run()
    {
        $this->console->run();
    }

    protected function consoleInitialize()
    {
        $this->console = new Console();
        $this->console->add(new ParsingManager());
    }

    /**
     * Returns the project directory in which the framework is installed
     *
     * @return string
     */
    protected function getProjectDir()
    {
        $dir = __DIR__;

        while (!file_exists($dir . '/composer.json')) {

            if ($dir === '/') {
                throw new \RuntimeException('File composer.json not found in project');
            }

            $dir = dirname($dir);
        }

        return $dir;
    }

    protected function laodDotenv(Dotenv $dotenv)
    {
        $dotenvPath = sprintf('%s/.env', $this->projectDir);

        if (!file_exists($dotenvPath)) {
            throw new \RuntimeException('File .env not found in project');
        }

        $dotenv->load($this->projectDir . '/.env');
    }
}