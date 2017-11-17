<?php

namespace Ig0rbm\Webcrawler\Box;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Ig0rbm\HandyBox\HandyBoxInterface;
use Ig0rbm\HandyBox\HandyBoxContainer;

/**
 * This box configurate doctrine orm
 *
 * @author 1g0rbm <m1g0rb89@gmail.com>
 */
class ORMDoctrineBox implements HandyBoxInterface
{
    /**
     * @param HandyBoxContainer $container
     * @param string $pathToEntities
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

        $config = Setup::createAnnotationMetadataConfiguration(
            [$container->storage()->get('path_to_entities', '')],
            getenv('DEV')
        );

        $container->service('em', function () use ($connectionParams, $config) {
            return EntityManager::create($connectionParams, $config);
        });
    }
}
