<?php

namespace Ig0rbm\Webcrawler\Box;

use Ig0rbm\HandyBox\HandyBoxInterface;
use Ig0rbm\HandyBox\HandyBoxContainer;
use Ig0rbm\Webcrawler\Exception\BadBoxException;

/**
 * @package Ig0rbm\Webcrawler
 * @author 1g0rbm <m1g0rb89@gmail.com>
 */
class UnitFactoryBox implements HandyBoxInterface
{
    public function register(HandyBoxContainer $container)
    {
        $container->factory('unit.factory', function ($classname) use ($container) {
            if (false === class_exists($classname)) {
                throw new \InvalidArgumentException(sprintf('Parsing unit not found by class name "%s"', $classname));
            }

            if (false === $container->storage()->get('redis.connection_status')) {
                throw new BadBoxException(__CLASS__);
            }

            $pPredis = $container->get('parser.predis');
            $stepName = strtolower(substr(strrchr($classname, "\\"), 1));

            $status = $pPredis->get($stepName . '.status');

            return new $classname($container, $status);
        });
    }
}
