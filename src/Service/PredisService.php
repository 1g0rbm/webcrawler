<?php

namespace Ig0rbm\Webcrawler\Service;

use Predis\Client as Predis;

/**
 * @package Ig0rbm\Webcrawler
 * @author 1g0rbm <m1g0rb89@gmail.com>
 */
class PredisParserService
{
    private $predis;
    private $prefix;

    /**
     * PredisParserService constructor.
     * @param Predis $predis
     */
    public function __construct(Predis $predis)
    {
        $this->predis = $predis;
        $this->prefix = getenv('PREDIS_PARSER_PREFIX');
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, $value)
    {
        $this->predis->set($this->key($key), $value);
    }

    /**
     * @param string $key
     * @return string
     */
    public function get(string $key)
    {
        return $this->predis->get($this->key($key));
    }

    /**
     * @param string $key
     * @return string
     */
    private function key(string $key)
    {
        return "{$this->prefix}_$key";
    }
}