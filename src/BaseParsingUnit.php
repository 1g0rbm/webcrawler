<?php

namespace Ig0rbm\Webcrawler;

use Ig0rbm\HandyBox\HandyBoxContainer;
use Ig0rbm\Prettycurl\Request\Request;
use Ig0rbm\Prettycurl\Response\Response;

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
     * @var string
     */
    protected $uri;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @param HandyBoxContainer $container
     * @param Request $request
     * @param string $uri
     */
    public function __construct(HandyBoxContainer $container, Request $request, string $uri)
    {
        $this->container = $container;
        $this->request = $request;
        $this->uri = $uri;
    }

    public function run()
    {
        $this->requestSettings();
        $this->response = $this->request->send($this->uri);
        $this->responseHandle();
        $this->save();
    }

    /**
     * @return array
     */
    abstract public function requestSettings();

    /**
     * @return void
     */
    abstract public function responseHandle();

    /**
     * @return void
     */
    abstract public function save();

    /*
     * @return string
     */
    protected function getPathForTemporaryFiles()
    {
        return sprintf(
            '%s/%s/%s',
            $this->container->storage()->get('path_to.var_dir'),
            $this->container->storage()->get('parser_name')
        );
    }
}