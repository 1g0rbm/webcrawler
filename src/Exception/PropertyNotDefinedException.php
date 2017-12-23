<?php

namespace Ig0rbm\Webcrawler\Exception;

/**
 * @package Ig0rbm\Webcrawler
 * @author 1g0rbm <m1g0rb89@gmail.com>
 */
class PropertyNotDefinedException extends \Exception
{
    /**
     * @param string $name
     * @param string $class
     */
    public function __construct(string $name, string $class)
    {
        parent::__construct(sprintf('Property "%s" in class "%s" is not defined.', $name));
    }
}
