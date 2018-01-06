<?php

namespace Ig0rbm\Webcrawler;

/**
 * @package Ig0rbm\Webcrawler
 * @author 1g0rbm <m1g0rb89@gmail.com>
 */
class ParserBuilder
{
    private $domain;
    private $name;
    private $rootNamespace;
    private $chain;

    /**
     * @param array $fields
     */
    public function __construct(array $fields)
    {
        $this->domain = $fields['domain'] ?? null;
        $this->name = $fields['name'] ?? null;
        $this->rootNamespace = $fields['namespace'] ?? null;
        $this->chain = $fields['chain'] ?? [];
    }

    /**
     * @param array $chain
     * 
     * @return $this
     */
    public function setChain(array $chain)
    {
        $this->chain = $chain;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getChain()
    {
        return $this->chain;
    }

    /**
     * @param string $rootNamespace
     * 
     * @return $this
     */
    public function setRootNamespace(string $rootNamespace)
    {
        $this->rootNamespace = $rootNamespace;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRootNamespace()
    {
        return $this->rootNamespace;
    }

    /**
     * @param string $name
     * 
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $domain
     * 
     * @return $this
     */
    public function setDomain(string $domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @return \ArrayIterator
     */
    public function getChainIterator()
    {
        return new \ArrayIterator($this->chain);
    }
    /**
     * @param \Closure $callback
     */
    public function chainWalk(\Closure $callback)
    {
        $cit = $this->getChainIterator();
        while ($cit->valid()) {
            $callback($cit->key(), $cit->current());
            $cit->next();
        }
    }
}