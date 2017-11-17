<?php

namespace Ig0rbm\Webcrawler;

use Ig0rbm\HandyBox\HandyBoxContainer;
use Ig0rbm\Prettycurl\Request\Request;

abstract class ParserBase
{
    protected $request;
    protected $container;

    public function __construct(HandyBoxContainer $container, Request $request)
    {
        $this->container = $container;
        $this->request = $request;
    }

    abstract public function data();

    abstract public function process();

    abstract public function save();
}
