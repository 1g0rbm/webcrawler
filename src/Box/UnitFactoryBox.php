<?php

namespace Ig0rbm\Webcrawler\Box;

use Ig0rbm\HandyBox\HandyBoxInterface;
use Ig0rbm\HandyBox\HandyBoxContainer;

class UnitFactoryBox implements HandyBoxInterface
{
    public function register(HandyBoxContainer $container)
    {
        $container->factory('unit.factory', function ($classname) use ($container) {
            if (false === class_exists($classname)) {
                throw new \InvalidArgumentException(sprintf('Parsing unit not found by class name "%s"', $classname));
            }

            return new $classname($container);
        });
    }
}
