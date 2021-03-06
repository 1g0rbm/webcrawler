<?php

namespace Ig0rbm\Webcrawler;

use Ig0rbm\HandyBox\HandyBoxContainer;
use Ig0rbm\Prettycurl\Request\Request;
use Ig0rbm\Prettycurl\Response\Response;
use Ig0rbm\Webcrawler\Service\PredisParserService;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @package Ig0rbm\Webcrawler
 * @author 1g0rbm <m1g0rb89@gmail.com>
 */
abstract class BaseParsingUnit
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var HandyBoxContainer
     */
    protected $container;

    /**
     * @var PredisParserService
     */
    protected $predis;

    /**
     * @var int
     */
    protected $status;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * BaseParsingUnit constructor.
     * @param HandyBoxContainer $container
     * @param int|null $status
     */
    public function __construct(HandyBoxContainer $container, $status = null)
    {
        $this->container = $container;
        $this->request = $container->storage()->get('parser.request');
        $this->status = $status ?: ParserKernel::READY;
    }

    /**
     * @param \Closure $callback
     */
    public function run(\Closure $callback = null)
    {
        if (method_exists($this, 'requestSettings')) {
            $this->requestSettings();
        }

        $this->process($callback);
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    public function getStepName()
    {
        $r = new \ReflectionClass($this);

        return strtolower($r->getShortName());
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    public function getStepHash()
    {
        $r = new \ReflectionClass($this);

        return md5($r->getName());
    }

    /**
     * @param \Closure $callback
     * @return mixed
     */
    abstract public function process(\Closure $callback = null);

    /**
     * @return PredisParserService|mixed
     * @throws \ReflectionException
     */
    protected function getPredis()
    {
        if (null === $this->predis) {
            $this->predis = $this->container->get('parser.predis');
            $this->predis->setPrefix($this->getStepHash());
        }

        return $this->predis;
    }

    /**
     * @param string|null $uri
     * @return string
     * @throws \ReflectionException
     */
    protected function makeRequest(string $uri = null)
    {
        $uri = $uri ?: $this->container->storage()->get($this->getStepName() . '.uri');

        $response = $this->request->send($uri);

        $fs = new Filesystem();

        if (false === $fs->exists($this->getPathForTemporaryFiles())) {
            $fs->mkdir($this->getPathForTemporaryFiles());
        }

        $fs->dumpFile(
            $this->getPathForTemporaryFiles() . sprintf('/%s.html', md5($uri)),
            $response->getBody()
        );

        return $response->getBody();
    }

    protected function getPathForTemporaryFiles()
    {
        return sprintf(
            '%s/%s',
            $this->container->storage()->get('path_to.var_dir'),
            $this->container->storage()->get('parser_name')
        );
    }
}