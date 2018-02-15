<?php

namespace Ig0rbm\Webcrawler\Box;

use Doctrine\ORM\Proxy\ProxyFactory;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Ig0rbm\HandyBox\HandyBoxInterface;
use Ig0rbm\HandyBox\HandyBoxContainer;

/**
 * This box configurate doctrine orm
 *
 * @package Ig0rbm\Webcrawler
 * @author 1g0rbm <m1g0rb89@gmail.com>
 */
class ORMDoctrineBox implements HandyBoxInterface
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

        $config = Setup::createAnnotationMetadataConfiguration(
            [$container->storage()->get('path_to.entities', '')],
            getenv('DEV')
        );
        $config->setProxyDir($container->storage()->get('path_to.proxy_files', ''));

        if (getenv('DEV') === 'dev') {
            $config->setAutoGenerateProxyClasses(ProxyFactory::AUTOGENERATE_EVAL);
        } else {
            $config->setAutoGenerateProxyClasses(ProxyFactory::AUTOGENERATE_FILE_NOT_EXISTS);
        }

        $container->service('em', function () use ($connectionParams, $config) {
            return EntityManager::create($connectionParams, $config);
        });
    }
}
