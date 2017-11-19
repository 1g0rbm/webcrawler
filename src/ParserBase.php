<?php

namespace Ig0rbm\Webcrawler;

use Ig0rbm\HandyBox\HandyBoxContainer;
use Ig0rbm\Prettycurl\Request\Request;

abstract class ParserBase
{
    protected $container;

    public function __construct(HandyBoxContainer $container)
    {
        $this->container = $container;
        $this->request = $request;
    }

    abstract public function settings();

    abstract public function process();

    abstract public function save();
}
