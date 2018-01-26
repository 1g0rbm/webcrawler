<?php

namespace Ig0rbm\Webcrawler\Exception;

use Throwable;

/**
 * @package Ig0rbm\Webcrawler
 * @author 1g0rbm <m1g0rb89@gmail.com>
 */
class BadBoxException extends \Exception
{
    /**
     * @var string
     */
    private $name;

    /**
     * @param string $name
     * @param string $message
     */
    public function __construct(string $name, string $message = "Box can not create a service.")
    {
        $this->name = $name;
        parent::__construct($message);
    }

    /**
     * @return string
     */
    public function getBoxName()
    {
        return $this->name;
    }
}