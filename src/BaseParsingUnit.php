<?php

use Ig0rbm\Prettycurl\Request\Request;

namespace Ig0rbm\Webcrawler;

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
     * @param Request $request
     * @return void
     */
    public function init(Request $request)
    {
        $this->request = $request;
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