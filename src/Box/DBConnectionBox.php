<?php

namespace Ig0rbm\Webcrawler\Box;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Configuration;
use Ig0rbm\HandyBox\HandyBoxInterface;
use Ig0rbm\HandyBox\HandyBoxContainer;

/**
 * This box set and configure connection to dab via DBAL
 *
 * @author 1g0rbm <m1g0rb89@gmail.com>
 */
class DBConnectionBox implements HandyBoxInterface
{
    public function register(HandyBoxContainer $container)
    {
        $DBALConfig = new Configuration();
        
        $connectionParams = [
                    'dbname' => getenv('DB_NAME'),
                    'user' => getenv('DB_USER'),
                    'password' => getenv('DB_PASSWORD'),
                    'host' => getenv('DB_HOST'),
                    'driver' => getenv('DB_DRIVER')
        ];

        $container->set('dbal', function () use ($connectionParams, $DBALConfig) {
            return DriverManager::getConnection($connectionParams, $DBALConfig);
        });
    }
}
