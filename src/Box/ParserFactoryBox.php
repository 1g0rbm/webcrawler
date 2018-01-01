<?php

namespace Ig0rbm\Webcrawler\Box;

use Ig0rbm\HandyBox\HandyBoxInterface;
use Ig0rbm\HandyBox\HandyBoxContainer;

class ParserFactoryBox implements HandyBoxInterface
{
    public function register(HandyBoxContainer $container)
    {
        $container->factory('parser.factory', function (string $name, array $fields) use ($container) {
            $name = sprintf('App\Parser\%s\%sParserKernel', $name, $name);

            if (false === class_exists($name)) {
                throw new \RuntimeException(sprintf('Class "%s" was not found.', $name));
            }

            return new $name($container, $fields);
        });
    }
}
