<?php

namespace Ig0rbm\Webcrawler;

use Ig0rbm\HandyBag\HandyBag;
use Ig0rbm\HandyBox\HandyBoxContainer;
use Ig0rbm\Prettycurl\Request\Request;
use Ig0rbm\Webcrawler\BaseParsingUnit;
use Ig0rbm\Webcrawler\Exception\RunNotReadyParserException;
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
     * @var \Closure
     */
    protected $before;

    /**
     * @var \Closure
     */
    protected $after;

    /**
     * @var \Closure
     */
    protected $during;

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
        $builder->chainWalk(function ($key, $chainUnit) use ($container, $builder, $kernel) {
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
            $container->storage()->set(
                'parser.request',
                isset($chainUnit['domain'])
                    ? $container->fabricate('prettycurl', $chainUnit['domain'])
                    : $this->getRequest()
            );
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

    /**
     * @param int|null $stepNumber
     * @return $this
     * @throws NotFoundException
     * @throws RunNotReadyParserException
     * @throws \ReflectionException
     */
    public function run(int $stepNumber = null)
    {
        if ($stepNumber) {
            $this->runUnit($this->getUnitNumberByStepNumber($stepNumber));
        } else {
            foreach ($this->parsingChain as $key => $unit) {
                try {
                    $this->runUnit($key);
                } catch (RunNotReadyParserException $e) {
                    continue;
                }
            }
        }

        return $this;
    }

    /**
     * @param \Closure $closure
     * @return $this
     */
    public function setBefore(\Closure $closure)
    {
        $this->before = $closure;

        return $this;
    }

    /**
     * @param \Closure $closure
     * @return $this
     */
    public function setAfter(\Closure $closure)
    {
        $this->after = $closure;

        return $this;
    }

    /**
     * @param \Closure $closure
     * @return $this
     */
    public function setDuring(\Closure $closure)
    {
        $this->during = $closure;

        return $this;
    }

    /**
     * @param int $unitNumber
     * @throws NotFoundException
     * @throws RunNotReadyParserException
     * @throws \ReflectionException
     */
    private function runUnit(int $unitNumber)
    {
        if (false === isset($this->parsingChain[$unitNumber])) {
            throw new NotFoundException('Unit of parsing not found');
        }

        /** @var $unit BaseParsingUnit */
        $unit = $this->parsingChain[$unitNumber];

        if ($unit->getStatus() !== ParserKernel::READY) {
            throw new RunNotReadyParserException($unit->getStepName());
        }

        if (is_callable($this->before)) {
            call_user_func($this->before, $unit->getStepName());
        }

        $unit->run($this->during);

        if (is_callable($this->after)) {
            call_user_func($this->after, $unit->getStepName());
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

    /**
     * @param int $unitNumber
     * @return int
     */
    private function getUnitNumberByStepNumber(int $unitNumber)
    {
        $unitNumber--;
        return $unitNumber;
    }
}
