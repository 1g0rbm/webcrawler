<?php

namespace Ig0rbm\Webcrawler\Exception;

/**
 * @package Ig0rbm\Webcrawler
 * @author 1g0rbm <m1g0rb89@gmail.com>
 */
class NotInstantiatedException extends \Exception
{
    /**
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct(sprintf('Object %s is not instantiated.', $name));
    }
}
