<?php

namespace Ig0rbm\Webcrawler\Box;

use Ig0rbm\HandyBox\HandyBoxInterface;
use Ig0rbm\HandyBox\HandyBoxContainer;

/**
 * @package Ig0rbm\Webcrawler
 * @author 1g0rbm <m1g0rb89@gmail.com>
 */
class UnitFactoryBox implements HandyBoxInterface
{
    public function register(HandyBoxContainer $container)
    {
        $container->factory('unit.factory', function ($classname, $uri, $request) use ($container) {
            if (false === class_exists($classname)) {
                throw new \InvalidArgumentException(sprintf('Parsing unit not found by class name "%s"', $classname));
            }

            return new $classname($container, $request, $uri);
        });
    }
}
