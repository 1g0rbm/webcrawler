<?php

namespace Ig0rbm\Webcrawler\Box;

use Ig0rbm\HandyBag\HandyBag;
use Ig0rbm\HandyBox\HandyBoxInterface;
use Ig0rbm\HandyBox\HandyBoxContainer;

class HandyBagBox implements HandyBoxInterface
{
    public function register(HandyBoxContainer $container)
    {
        $container->factory('handybag', function (arary $collection = null) {
            $collection = null === $collection ? [] : $collection;
            return new HandyBag($collection);
        });
    }
}
