<?php

namespace Ig0rbm\Webcrawler\Console;

use Symfony\Component\Console\Command\Command;
use Ig0rbm\HandyBag\HandyBag;

/**
 * @package Ig0rbm\Webcrawler
 * @author 1g0rbm <m1g0rb89@gmail.com>
 */
class BaseParserConsole extends Command
{
    /**
     * @var HandyBag
     */
    protected $parsers;

    /**
     * @var ParserKernel
     */
    protected $currentParser;

    /**
     * @var array
     */
    protected $stdOut = [];

    public function __construct(HandyBag $parsers)
    {
        $this->parsers = $parsers;

        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    public function setName($name)
    {
        parent::setName($name);

        $this
            ->pushToStdOut(sprintf('<fg=black;bg=green>*%s*</>', $name))
            ->pushToStdOut('');

        return $this;
    }

    /**
     * @param string $name
     * 
     * @return $this;
     */
    protected function setCurrentParserByName(string $name)
    {
        if (!$this->parsers->has($name)) {
            return false;
        }

        $this->currentParser = $this->parsers->get($name);

        return $this;
    }

    /**
     * @param string|array $stdOut
     * @return $this
     */
    protected function pushToStdOut($stdOut)
    {
        if (is_array($stdOut)) {
            $this->stdOut = array_merge($this->stdOut, $stdOut);
        } elseif (is_string($stdOut)) {
            $this->stdOut[] = $stdOut;
        } else {
            $message = 'Method pushToStdOut only accepted string or array. Input was ' . $stdOut . '.';
            throw new InvalidArgumentException($message);
        }

        return $this;
    }
}