<?php

namespace Ig0rbm\Webcrawler\Box;

use Ig0rbm\HandyBox\HandyBoxInterface;
use Ig0rbm\HandyBox\HandyBoxContainer;
use Ig0rbm\Webcrawler\ParserKernel;
use Ig0rbm\Webcrawler\ParserBuilder;

/**
 * @package Ig0rbm\Webcrawler
 * @author 1g0rbm <m1g0rb89@gmail.com>
 */
class ParserFactoryBox implements HandyBoxInterface
{
    public function register(HandyBoxContainer $container)
    {
        $container->factory('parser.factory', function (array $fields) use ($container) {
            return new ParserKernel($container, new ParserBuilder($fields));
        });
    }
}
