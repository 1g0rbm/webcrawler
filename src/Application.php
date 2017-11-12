<?php

namespace Ig0rbm\Webcrawler;

use Symfony\Component\Console\Application as Console;
use Doctrine\DBAL\Connection as DBAL;

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

    /**
     * @var DBAL
     */
    private $dbal;

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