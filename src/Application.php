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

    public function __construct()
    {
        $this->consoleInitialize();
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
}