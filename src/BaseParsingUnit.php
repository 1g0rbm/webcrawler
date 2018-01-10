<?php

namespace Ig0rbm\Webcrawler;

use Ig0rbm\HandyBox\HandyBoxContainer;
use Ig0rbm\Prettycurl\Request\Request;
use Ig0rbm\Prettycurl\Response\Response;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

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
     * @var array
     */
    protected $data = [];

    /**
     * @param HandyBoxContainer $container
     */
    public function __construct(HandyBoxContainer $container)
    {   
        $this->container = $container;
        $this->request = $container->storage()->get('parser_request');
    }

    public function run()
    {
        $this->requestSettings();
        $this->process();
    }

    /**
     * @return array
     */
    abstract public function requestSettings();

    /**
     * @return void
     */
    abstract public function process();

    /**
     * @param string $uri
     * 
     * @return string
     */
    protected function makeRequest(string $uri = null)
    {
        $uri = $uri ?: $this->container->storage()->get('parser_uri');

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

    /*
     * @return string
     */
    protected function getPathForTemporaryFiles()
    {
        return sprintf(
            '%s/%s',
            $this->container->storage()->get('path_to.var_dir'),
            $this->container->storage()->get('parser_name')
        );
    }
}