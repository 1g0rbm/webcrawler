<?php

namespace Ig0rbm\Webcrawler\Box;

use Ig0rbm\Prettycurl\PrettyCurl;
use Ig0rbm\HandyBox\HandyBoxInterface;
use Ig0rbm\HandyBox\HandyBoxContainer;

class PrettyCurlBox implements HandyBoxInterface
{
    public function register(HandyBoxContainer $container)
    {
        $container->factory('prettycurl', function (string $domain) {
            return PrettyCurl::getRequestInstance($domain);
        });
    }
}
