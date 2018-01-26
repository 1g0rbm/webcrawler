<?php

namespace Ig0rbm\Webcrawler;

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
            echo "\033[41m -------------------------------------------------------------------" . PHP_EOL;
            echo "\033[41m  Oooops! Looks like something went wrong " . PHP_EOL;
            echo "\033[41m\e[41m -------------------------------------------------------------------\033[0m" . PHP_EOL;
            echo PHP_EOL;
            echo "-------------------------------------------------------------------" . PHP_EOL;
            echo "  Error message: {$this->e->getMessage()} " . PHP_EOL;
            echo "-------------------------------------------------------------------" . PHP_EOL;
            echo PHP_EOL;

            if (getenv('ENV') === 'dev') {
                echo "  Stacktrace: " . PHP_EOL;
                echo PHP_EOL;

                foreach ($this->e->getTrace() as $number => $line) {
                    echo "-------------------------------------------------------------------" . PHP_EOL;
                    echo " Line $number: " . PHP_EOL;
                    echo "-------------------------------------------------------------------" . PHP_EOL;

                    $this->printPart($line);
                }

                echo "-------------------------------------------------------------------" . PHP_EOL;
            }

            die;
        } else {
            //TODO http error handling
        }
    }

    private function printPart(array $parts)
    {
        foreach ($parts as $key => $part) {
            if (is_array($part)) {
                $this->printPart($part);
            } elseif (is_callable($part)) {
                echo " $key: callable()" . PHP_EOL;
            } elseif (is_object($part)) {
                $classname = get_class($part);
                echo " $key: $classname()" . PHP_EOL;
            } else {
                echo " $key: $part " . PHP_EOL;
            }
        }
    }
}