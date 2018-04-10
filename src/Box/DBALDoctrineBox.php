<?php

namespace Ig0rbm\Webcrawler\Box;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Ig0rbm\HandyBox\HandyBoxInterface;
use Ig0rbm\HandyBox\HandyBoxContainer;

/**
 * This box configurate doctrine orm
 *
 * @package Ig0rbm\Webcrawler
 * @author 1g0rbm <m1g0rb89@gmail.com>
 */
class DBALDoctrineBox implements HandyBoxInterface
{
    /**
     * @param HandyBoxContainer $container
     */
    public function register(HandyBoxContainer $container)
    {
        $connectionParams = [
            'dbname' => getenv('DB_NAME'),
            'user' => getenv('DB_USER'),
            'password' => getenv('DB_PASSWORD'),
            'host' => getenv('DB_HOST'),
            'driver' => getenv('DB_DRIVER')
        ];

        $config = new Configuration();

            $container->service('dbal', function () use ($connectionParams, $config) {
                return DriverManager::getConnection($connectionParams, $config);
            });
    }
}
