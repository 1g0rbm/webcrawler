<?php

namespace Ig0rbm\Webcrawler;

use Symfony\Component\Console\Application as Console;

/**
 * @package Ig0rbm\Webcrawler
 * @author 1g0rbm <m1g0rb89@gmail.com>
 */
class Application
{
    private $console;

    public function __construct()
    {
        $this->console = new Console();
    }

    public function initialize()
    {
        $this->console->add(new ParsingManager());
    }

    public function run()
    {
        $this->console->run();
    }
}