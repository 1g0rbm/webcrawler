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
     * @param $value
     * @return mixed
     */
    public function set(string $key, $value)
    {
        return $this->predis->set($this->key($key), $value);
    }

    /**
     * @param string $key
     * @param $value
     * @return int
     */
    public function lpush(string $key, $value)
    {
        return $this->predis->lpush($this->key($key), $value);
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
     * @return array
     */
    public function allKeys()
    {
        $keys = [];
        $keysWithPrefix = $this->predis->keys($this->key('*'));

        foreach ($keysWithPrefix as $key) {
            $keys[] = $this->getKeyWithoutPrefix($key);
        }

        return $keys;
    }

    /**
     * @return array
     */
    public function all()
    {
        $response = [];
        $keys = $this->allKeys();

        foreach ($keys as $key) {
            $response[$key] = $this->get($key);
        }

        return $response;
    }

    /**
     * @param string|array $key
     * @return int
     */
    public function delete($key)
    {

        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $key[$k] = $this->key($v);
            }
        } else {
            $key = $this->key($key);
        }

        if (empty($key)) {
            return 0;
        }

        return $this->predis->del($key);
    }

    /**
     * @return int
     */
    public function deleteAll()
    {
        $keys = $this->allKeys();

        return $this->delete($keys);
    }

    /**
     * @param string $key
     * @return string
     */
    private function key(string $key)
    {
        return "{$this->prefix}_$key";
    }

    /**
     * @param string $key
     * @return mixed
     */
    private function getKeyWithoutPrefix(string $key)
    {
        return str_replace("{$this->prefix}_", '', $key);
    }
}