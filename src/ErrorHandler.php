<?php

namespace Ig0rbm\Webcrawler;

/**
 * @package Ig0rbm\Webcrawler
 * @author 1g0rbm <m1g0rb89@gmail.com>
 */
class ErrorHandler
{
    const CLI_SAPI = 'cli';

    private $e;

    public function __construct(\Exception $e)
    {
        $this->e = $e;
    }

    public function handle()
    {
        if (php_sapi_name() === self::CLI_SAPI) {
            (new ErrorHandlerConsole($this->e))->printException();
        } else {
            //TODO http error handling
        }
    }
}