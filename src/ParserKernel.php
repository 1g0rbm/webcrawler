<?php

namespace Ig0rbm\Webcrawler;

use Ig0rbm\HandyBag\HandyBag;
use Ig0rbm\HandyBox\HandyBoxContainer;
use Ig0rbm\Prettycurl\Request\Request;
use Ig0rbm\Webcrawler\ParserChainLinkInterface;

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
     * @var HandyBag
     */
    protected $parsingChain;

    /**
     * Set instance of DI container
     *
     * @param HandyBoxContainer $container
     *
     * @return void
     */
    public function prepare(HandyBoxContainer $container, HandyBag $parsingChain)
    {
        $this->status = static::$statusText[static::NOT_READY];
        $this->container = $container;

        // This should be after the initialization of the container
        // TODO Separate the initialization of the container from application initialization
        $this->parsingChain = $this->container->fabricate('handybag');
        $this->instantiateRequest($this->domain);
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return HandyBag
     */
    public function parsingChain()
    {
        return $this->parsingChain;
    }

    /**
     * @return string the name of parser
     * @throws \RuntimeException
     */
    public function getName()
    {
        if (null === $this->name) {
            throw new \RuntimeException('Can not find the name of the parser');
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
     * @param string $domain
     * 
     * @return void
     */
    protected function instantiateRequest(string $domain)
    {
        $this->request = $this->container->fabricate('prettycurl', $domain);
    }
}
