<?php

namespace Ig0rbm\Webcrawler;

use Symfony\Component\Console\Application as Console;

/**
 * @package Ig0rbm\Webcrawler
 * @author 1g0rbm <m1g0rb89@gmail.com>
 */
class Application
{
    /**
     * @var Console
     */
    private $console;

    private $projectDir;

    public function __construct()
    {
        $this->consoleInitialize();

        $this->projectDir = $this->getProjectDir();
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
            $dir = dirname($dir);
        }

        return $dir;
    }
}