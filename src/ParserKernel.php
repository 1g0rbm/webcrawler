<?php

namespace Ig0rbm\Webcrawler;

use Ig0rbm\HandyBag\HandyBag;
use Ig0rbm\HandyBox\HandyBoxContainer;
use Ig0rbm\Prettycurl\Request\Request;
use Ig0rbm\Webcrawler\BaseParsingUnit;
use Ig0rbm\Webcrawler\ParserBuilder;
use Ig0rbm\Webcrawler\Exception\PropertyNotDefinedException;
use Ig0rbm\Webcrawler\Exception\NotFoundException;

/**
 * @package Ig0rbm\Webcrawler
 * @author 1g0rbm <m1g0rb89@gmail.com>
 */
class ParserKernel
{
    const NOT_READY = 0;
    const READY = 1;
    const PARSING = 2;
    const DONE = 3;
    const ERROR = 4;

    public static $statusText = [
        self::NOT_READY => 'not ready',
        self::READY => 'ready',
        self::PARSING => 'in progress',
        self::DONE => 'done',
        self::ERROR => 'error'
    ];

    protected $name;
    protected $domain;
    protected $status;

    /**
     * @var HandyBag
     */
    protected $container;
    
    /**
     * @var Request
     */
    protected $request;
    
    /**
     * @var array
     */
    protected $parsingChain = [];

    /**
     * ParserKernel constructor.
     * @param HandyBoxContainer $container
     * @param ParserBuilder $builder
     * @throws PropertyNotDefinedException
     */
    public function __construct(HandyBoxContainer $container, ParserBuilder $builder)
    {
        $this->container = $container;

        $this->domain = $builder->getDomain();
        $this->name = $builder->getName();
        $this->request = $this->container->fabricate('prettycurl', $this->domain);

        $kernel = $this;
        $builder->chainWalk(function($key, $chainUnit) use ($container, $builder, $kernel) {
            //TODO need validator for parser.yml
            if (false === isset($chainUnit['class'])) {
                throw new NotFoundException(sprintf('Parameter "class" not found in parser "%s" config.', $builder->getName()));
            }

            $classname = sprintf(
                '%s\%s\%s',
                $builder->getRootNamespace(),
                $builder->getName(),
                $chainUnit['class']
            );

            $container->storage()->set('parser.name', $builder->getName());
            $container->storage()->set('parser.request', $this->request);
            $container->storage()->set(strtolower($chainUnit['class']) . '.uri', $chainUnit['uri'] ?? null);

            $unit = $container->fabricate('unit.factory', $classname);

            $kernel->pushUnitToChain($unit);
        });

        $this->setStatus();
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return null|string
     * @throws PropertyNotDefinedException
     */
    public function getName()
    {
        if (null === $this->name) {
            throw new PropertyNotDefinedException('name', self::class);
        }

        return $this->name;
    }

    /**
     * @return int current parser statas
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     * @throws PropertyNotDefinedException
     */
    public function getStatusText()
    {
        if (null === $this->status) {
            throw new PropertyNotDefinedException('status', self::class);
        }

        return static::$statusText[$this->status];
    }

    public function getChainLength()
    {
        return count($this->parsingChain);
    }

    /**
     * @param BaseParsingUnit $unit
     *
     * @return $this
     */
    public function pushUnitToChain(BaseParsingUnit $unit)
    {
        $this->parsingChain[] = $unit;

        return $this;
    }

    public function run()
    {
        foreach($this->parsingChain as $key => $unit) {
            if ($unit->getStatus() === ParserKernel::READY) {
                $unit->run();
            }
        }
    }

    /**
     * @throws PropertyNotDefinedException
     */
    private function setStatus()
    {
        $ready = true;

        if (!$this->getName()) {
            $ready = false;
        }

        if (!$this->getRequest()) {
            $ready = false;
        }

        if ($this->getChainLength() <= 0) {
            $ready = false;
        }

        $this->status = $ready ? static::READY : static::NOT_READY;
    }
}
