<?php

namespace Ig0rbm\Webcrawler\Box;

use Ig0rbm\HandyBox\HandyBoxInterface;
use Ig0rbm\HandyBox\HandyBoxContainer;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

/**
 * @package Ig0rbm\Webcrawler
 * @author 1g0rbm <m1g0rb89@gmail.com>
 */
class DoctrineConsoleRunnerBox implements HandyBoxInterface
{
    public function register(HandyBoxContainer $container)
    {
        $container->factory('console_runner', function () use ($container) {
            return ConsoleRunner::createHelperSet($container->get('em'));
        });
    }
}
