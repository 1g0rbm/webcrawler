<?php

namespace Ig0rbm\Webcrawler;

use Ig0rbm\HandyBag\HandyBag;
use Ig0rbm\HandyBox\HandyBoxContainer;
use Ig0rbm\Prettycurl\Request\Request;
use Ig0rbm\Webcrawler\BaseParsingUnit;
use Ig0rbm\Webcrawler\Exception\PropertyNotDefinedException;

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
    const ERROR = 4;

    public static $statusText = [
        self::NOT_READY => 'not ready',
        self::READY => 'ready',
        self::PARSING => 'in progress',
        self::DONE => 'done',
        self::ERROR => 'error'
    ];

    protected $name;
    protected $domain;
    protected $status;

    /**
     * @var HandyBox
     */
    protected $container;
    
    /**
     * @var Request
     */
    protected $request;
    
    /**
     * @var array
     */
    protected $parsingChain = [];

    /**
     * Set instance of DI container
     *
     * @param HandyBoxContainer $container
     *
     * @return void
     */
    public function prepare(HandyBoxContainer $container)
    {
        $this->container = $container;
        $this->instantiateRequest($this->domain);

        $this->setStatus();
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return string the name of parser
     * @throws PropertyNotDefineException
     */
    public function getName()
    {
        if (null === $this->name) {
            throw new PropertyNotDefinedException('name', self::class);
        }

        return $this->name;
    }

    /**
     * @return int current parser statas
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getStatusText()
    {
        if (null === $this->status) {
            PropertyNotDefinedException('status', self::class);
        }

        return static::$statusText[$this->status];
    }

    public function getChainLength()
    {
        return count($this->parsingChain);
    }

    /**
     * @param ParsingUnitInterface $parsingUnit
     * @return ParserKernel
     */
    public function pushUnit(BaseParsingUnit $parsingUnit)
    {
        $this->parsingChain[] = $parsingUnit;

        return $this;
    }

    private function setStatus()
    {
        $ready = true;

        if (!$this->getName()) {
            $ready = false;
        }

        if (!$this->getRequest()) {
            $ready = false;
        }

        if ($this->getChainLength() <= 0) {
            $ready = false;
        }

        $this->status = $ready ? static::READY : static::NOT_READY;
    }

    /**
     * @param string $domain
     * 
     * @return void
     */
    private function instantiateRequest(string $domain)
    {
        $this->request = $this->container->fabricate('prettycurl', $domain);
    }
}
