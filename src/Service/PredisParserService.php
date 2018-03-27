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

    /**
     * PredisParserService constructor.
     * @param Predis $predis
     */
    public function __construct(Predis $predis)
    {
        $this->predis = $predis;
    }

    /**
     * @param string $key
     * @param $value
     * @return mixed
     */
    public function set(string $key, $value)
    {
        return $this->predis->set($key, $value);
    }

    /**
     * @param string $key
     * @param $value
     * @return int
     */
    public function lpush(string $key, $value)
    {
        return $this->predis->lpush($key, $value);
    }

    /**
     * @param string $key
     * @return string
     */
    public function rpop(string $key)
    {
        return $this->predis->rpop($key);
    }

    /**
     * @param string $key
     * @return string
     */
    public function get(string $key)
    {
        return $this->predis->get($key);
    }

    /**
     * @return array
     */
    public function allKeys()
    {
        return $this->predis->keys('*');
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
        $result = 0;
        if (is_array($key)) {
            foreach ($key as $item) {
                $result += $this->predis->del($item);
            }
        } else {
            $result = $this->predis->del($key);
        }

        return $result;
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
     * @param string|array $key
     * @return int
     */
    public function exist($key)
    {
        $result = 0;
        if (is_array($key)) {
            foreach ($key as $item) {
                $result += $this->predis->exists($item);
            }
        } else {
            $result = $this->predis->exists($key);
        }

        return $result;
    }
}