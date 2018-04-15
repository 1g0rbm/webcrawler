<?php

namespace Ig0rbm\Webcrawler\Exception;

/**
 * @package Ig0rbm\Webcrawler
 * @author 1g0rbm <m1g0rb89@gmail.com>
 */
class RunNotReadyParserException extends \Exception
{
    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct(sprintf('Parser %s not ready to run. Check config.yml', $name));
    }
}
