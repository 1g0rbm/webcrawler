<?php

use Ig0rbm\Prettycurl\Request\Request;

namespace Ig0rbm\Webcrawler;

/**
 * @package Ig0rbm\Webcrawler
 * @author 1g0rbm <m1g0rb89@gmail.com>
 */
interface ParserChainLinkInterface
{
    public function __construct(Request $request);

    /**
     * @return array
     */
    public function preferences();

    /**
     * @return void
     */
    public function execute();

    /**
     * @return void
     */
    public function save();
}