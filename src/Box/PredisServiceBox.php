<?php

namespace Ig0rbm\Webcrawler\Box;

use Ig0rbm\HandyBox\HandyBoxInterface;
use Ig0rbm\HandyBox\HandyBoxContainer;
use Ig0rbm\Webcrawler\Service\PredisParserService;

/**
 * @package Ig0rbm\Webcrawler
 * @author 1g0rbm <m1g0rb89@gmail.com>
 */
class PredisServiceBox implements HandyBoxInterface
{
    public function register(HandyBoxContainer $container)
    {
        $container->service('parser.predis', function () use ($container) {
            return new PredisParserService($container->get('predis'));
        });
    }
}
