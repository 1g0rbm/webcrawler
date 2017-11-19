<?php

namespace Ig0rbm\Webcrawler\Box;

use Symfony\Component\DomCrawler\Crawler;
use Ig0rbm\HandyBox\HandyBoxInterface;
use Ig0rbm\HandyBox\HandyBoxContainer;

class DomCrawlerBox implements HandyBoxInterface
{
    public function register(HandyBoxContainer $container)
    {
        $container->factory('domcrawler', function (string $html) {
            return new Crawler($html);
        });
    }
}
