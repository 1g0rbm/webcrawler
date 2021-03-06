<?php

namespace Ig0rbm\Webcrawler\Box;

use Predis\Client as Predis;
use Ig0rbm\HandyBox\HandyBoxInterface;
use Ig0rbm\HandyBox\HandyBoxContainer;
use Ig0rbm\Webcrawler\Exception\BadBoxException;

/**
 * @package Ig0rbm\Webcrawler
 * @author 1g0rbm <m1g0rb89@gmail.com>
 */
class VanillaPredisServiceBox implements HandyBoxInterface
{
    public function register(HandyBoxContainer $container)
    {
        $container->service('predis', function () use ($container) {
            try {
                $predis = new Predis([
                    'schema' => getenv('REDIS_SCHEMA'),
                    'host' => getenv('REDIS_HOST'),
                    'port' => getenv('REDIS_PORT'),
                ]);

                $predis->set('test', 1);
                $predis->del('test');

                $container->storage()->set('redis.connection_status', true);
            } catch (\Predis\Connection\ConnectionException $e) {
                $container->storage()->set('redis.connection_status', false);
                throw new BadBoxException(__CLASS__, 'Connect to predis not found');
            }

            return $predis;
        });
    }
}
