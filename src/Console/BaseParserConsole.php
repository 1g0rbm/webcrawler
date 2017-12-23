<?php

namespace Ig0rbm\Webcrawler\Console;

use Symfony\Component\Console\Command\Command;
use Ig0rbm\HandyBag\HandyBag;

/**
 * @package Ig0rbm\Webcrawler
 * @author 1g0rbm <m1g0rb89@gmail.com>
 */
class BaseParserConsole extends Command
{
    /**
     * @var HandyBag
     */
    protected $parsers;

    /**
     * @var ParserKernel
     */
    protected $currentParser;

    /**
     * @var array
     */
    protected $stdOut = [];

    public function __construct(HandyBag $parsers)
    {
        $this->parsers = $parsers;

        parent::__construct();
    }
}