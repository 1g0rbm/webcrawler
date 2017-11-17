<?php

namespace Ig0rbm\Webcrawler\Box;

use Ig0rbm\HandyBox\HandyBoxInterface;
use Ig0rbm\HandyBox\HandyBoxContainer;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

class DoctrineConsoleRunnerBox implements HandyBoxInterface
{
    public function register(HandyBoxContainer $container)
    {
        $container->factory('console_runner', function () use ($container) {
            return ConsoleRunner::createHelperSet($container->get('em'));
        });
    }
}
