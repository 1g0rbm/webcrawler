<?php

namespace Ig0rbm\Webcrawler\Box;

use Ig0rbm\HandyBox\HandyBoxInterface;
use Ig0rbm\HandyBox\HandyBoxContainer;
use Ig0rbm\UrlResolver\UrlResolver;

class UrlResolverBox implements HandyBoxInterface
{
    public function register(HandyBoxContainer $container)
    {
        $container->factory('url.resolver', function (string $url) {
            return new UrlResolver($url);
        });
    }
}