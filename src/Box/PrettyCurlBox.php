<?php

namespace Ig0rbm\Webcrawler\Box;

use Ig0rbm\Prettycurl\PrettyCurl;
use Ig0rbm\HandyBox\HandyBoxInterface;
use Ig0rbm\HandyBox\HandyBoxContainer;

/**
 * @package Ig0rbm\Webcrawler
 * @author 1g0rbm <m1g0rb89@gmail.com>
 */
class PrettyCurlBox implements HandyBoxInterface
{
    public function register(HandyBoxContainer $container)
    {
        $container->factory('prettycurl', function (string $domain) {
            return PrettyCurl::getRequestInstance($domain);
        });
    }
}
