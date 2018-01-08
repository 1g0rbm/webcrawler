<?php

namespace Ig0rbm\Webcrawler\Box;

use Predis\Client as Predis;
use Ig0rbm\HandyBox\HandyBoxInterface;
use Ig0rbm\HandyBox\HandyBoxContainer;

/**
 * @package Ig0rbm\Webcrawler
 * @author 1g0rbm <m1g0rb89@gmail.com>
 */
class PredisServiceBox implements HandyBoxInterface
{
    public function register(HandyBoxContainer $container)
    {
        $container->service('predis', function () {
            return new Predis([
                'schema' => getenv('REDIS_SCHEMA'),
                'host' => getenv('REDIS_HOST'),
                'port' => getenv('REDIS_PORT'),
            ]);
        });
    }
}
