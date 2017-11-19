<?php

namespace Ig0rbm\Webcrawler;

use Ig0rbm\HandyBox\HandyBoxContainer;
use Ig0rbm\Prettycurl\Request\Request;

/**
 * @package Ig0rbm\Webcrawler
 * @author 1g0rbm <m1g0rb89@gmail.com>
 */
abstract class ParserKernel
{
    const NOT_READY = 0;
    const READY = 1;
    const PARSING = 2;
    const DONE = 3;

    public static $statuses = [
        self::NOT_READY => 'not.ready',
        self::READY => 'ready',
        self::PARSING => 'parsing',
        self::DONE => 'done'
    ];

    private $name;
    private $status;
    protected $container;

    public function __construct()
    {
        $this->status = NOT_READY;
    }

    /**
     * Set instance of DI container
     *
     * @param HandyBoxContainer $container
     * 
     * @return void
     */
    public function setContainer(HandyBoxContainer $container)
    {
        $this->container = $container;
    }

    /**
     * @return string the name of parser
     * #
     */
    public function getName()
    {
        if (null === $this->name) {
            throw new \RuntimeException('Can not find the name of the parser');
        }

        return $this->name;
    }

    protected function nominate()
    {
        $this->name = strtolower(__CLASS__);
    }
}
