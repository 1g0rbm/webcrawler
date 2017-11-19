<?php

namespace Ig0rbm\Webcrawler;

use Ig0rbm\HandyBox\HandyBoxContainer;
use Ig0rbm\HandyBag\HandyBag;
use Ig0rbm\Webcrawler\Box\ORMDoctrineBox;
use Ig0rbm\Webcrawler\Box\DoctrineConsoleRunnerBox;
use Ig0rbm\Webcrawler\Box\PrettyCurlBox;
use Symfony\Component\Console\Application as Console;
use Symfony\Component\Dotenv\Dotenv;

/**
 * @package Ig0rbm\Webcrawler
 * @author 1g0rbm <m1g0rb89@gmail.com>
 */
class CrawlerKernel
{
    /**
     * @var Console
     */
    private $console;

    /**
     * @var HandyBoxContainer
     */
    private $container;

    /**
     * @var HandyBag
     */
    private $parsers;

    /**
     * The absolute path to project directory
     */
    private $projectDir;

    public function __construct()
    {
        $this->projectDir = $this->getProjectDir();

        $this->loadDotenv(new Dotenv());

        $this->parsers = new HandyBag();

        $this->containerInitialize();
    }

    /**
     * @param string $name
     * @param ParserKernel $parser
     *
     * @return void
     */
    public function registerParser(ParserKernel $parser)
    {
        $parser->setContainer($this->container);
        $this->parsers->set($parser->getName(), $parser);
    }

    /**
     * Return dic
     *
     * @return HandyBoxContainer
     */
    public function container()
    {
        return $this->container;
    }

    /**
     * Run application
     *
     * @return void
     */
    public function runConsole()
    {
        $this->consoleInitialize();
        $this->console->run();
    }

    /**
     * Initialization default console command
     *
     * @return void
     */
    protected function consoleInitialize()
    {
        $this->console = new Console();

        $this->console->add(new ParsingManager($this->parsers));
    }

    /**
     * Initialization default dependencies
     *
     * @return void
     */
    protected function containerInitialize()
    {
        $this->container = new HandyBoxContainer();

        $this->container->storage()->set('path_to_entities', sprintf('%s/Entity', $this->projectDir));
        $this->container->register(new ORMDoctrineBox());
        $this->container->register(new DoctrineConsoleRunnerBox());
        $this->container->register(new PrettyCurlBox());
    }

    /**
     * Returns the project directory in which the framework is installed
     *
     * @return string
     */
    protected function getProjectDir()
    {
        $dir = __DIR__;

        while (!file_exists($dir . '/composer.json')) {
            if ($dir === '/') {
                throw new \RuntimeException('File composer.json not found in project');
            }

            $dir = dirname($dir);
        }

        return $dir;
    }

    /**
     * Load params from .env file from project root dir
     *
     * @param Dotenv $dotenv
     *
     * @return void
     */
    protected function loadDotenv(Dotenv $dotenv)
    {
        $dotenvPath = sprintf('%s/.env', $this->projectDir);

        if (!file_exists($dotenvPath)) {
            throw new \RuntimeException('File .env not found in project');
        }

        $dotenv->load($this->projectDir . '/.env');
    }
}
