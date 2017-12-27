<?php

namespace Ig0rbm\Webcrawler\Box;

use Ig0rbm\EventManager\EventManager;
use Ig0rbm\HandyBox\HandyBoxInterface;
use Ig0rbm\HandyBox\HandyBoxContainer;

class EventManagerBox implements HandyBoxInterface
{
    public function register(HandyBoxContainer $container)
    {
        $container->service('event-manager', function () {
            return new EventManager();
        });
    }
}
