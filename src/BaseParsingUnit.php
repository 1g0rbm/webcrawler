<?php

namespace Ig0rbm\Webcrawler;

use Ig0rbm\HandyBox\HandyBoxContainer;
use Ig0rbm\Prettycurl\Request\Request;

/**
 * @package Ig0rbm\Webcrawler
 * @author 1g0rbm <m1g0rb89@gmail.com>
 */
abstract class BaseParsingUnit
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var HandyBoxContainer
     */
    private $container;

    /**
     * @var string
     */
    private $uri;

    /**
     * @param HandyBoxContainer $container
     * @param Request $request
     * @param string $uri
     */
    public function __construct(HandyBoxContainer $container, Request $request, string $uri)
    {
        $this->container = $container;
        $this->request = $request;
        $this->uri = $uri;
    }

    /**
     * @return array
     */
    abstract public function preferences();

    /**
     * @return void
     */
    abstract public function execute();

    /**
     * @return void
     */
    abstract public function save();
}