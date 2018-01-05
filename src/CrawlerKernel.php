<?php

namespace Ig0rbm\Webcrawler;

use Ig0rbm\HandyBox\HandyBoxContainer;
use Ig0rbm\HandyBag\HandyBag;
use Ig0rbm\Webcrawler\Box\ORMDoctrineBox;
use Ig0rbm\Webcrawler\Box\HandyBagBox;
use Ig0rbm\Webcrawler\Box\DoctrineConsoleRunnerBox;
use Ig0rbm\Webcrawler\Box\PrettyCurlBox;
use Ig0rbm\Webcrawler\Box\DomCrawlerBox;
use Ig0rbm\Webcrawler\Box\EventManagerBox;
use Ig0rbm\Webcrawler\Box\ParserFactoryBox;
use Ig0rbm\Webcrawler\Box\UnitFactoryBox;
use Ig0rbm\Webcrawler\Console\ParsersInfo;
use Ig0rbm\Webcrawler\Console\ParsingRun;
use Symfony\Component\Console\Application as Console;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Yaml\Yaml;

/**
 * @package Ig0rbm\Webcrawler
 * @author 1g0rbm <m1g0rb89@gmail.com>
 */
class CrawlerKernel
{
    /**
     * @var Console
     */
    protected $console;

    /**
     * @var HandyBoxContainer
     */
    protected $container;

    /**
     * @var HandyBag
     */
    protected $parsers;

    /**
     * The absolute path to project directory
     * 
     * @var string
     */
    protected $projectDir;

    /**
     * The absolute path to directory with users parsers files
     *
     * @var string
     */
    protected $parsersDir;

    /**
     * The absolute path to directory with config for users parsers
     *
     * @var string
     */
    protected $parsersConfigPath;

    public function __construct()
    {
        $this->containerInitialize();

        // This should be after the initialization of the container
        // TODO Separate the initialization of the container from application initialization
        $this->parsers = $this->container->fabricate('handybag');
    }

    public function loadParsers()
    {
        $config = Yaml::parseFile($this->getParsersConfigPath());

        foreach ($config as $name => $settings) {
            $parser = $this->container->fabricate('parser.factory', $settings);

            $this->parsers->set($parser->getName(), $parser);
        }
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

        $this->console->add(new ParsersInfo($this->parsers));
        $this->console->add(new ParsingRun($this->parsers));
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
        $this->container->register(new DomCrawlerBox());
        $this->container->register(new HandyBagBox());
        $this->container->register(new EventManagerBox());
        $this->container->register(new ParserFactoryBox());
        $this->container->register(new UnitFactoryBox());
    }

    /**
     * Returns the project directory in which the framework is installed
     *
     * @return string
     * @throws \RuntimeException
     */
    protected function getProjectDir()
    {
        if (null !== $this->projectDir) {
            return $this->projectDir;
        }

        $r = new \ReflectionObject($this);

        $dir = dirname($r->getFileName());

        while (!file_exists($dir . '/composer.json')) {
            if ($dir === '/') {
                throw new \RuntimeException('File composer.json not found in project');
            }

            $dir = dirname($dir);
        }

        return $this->projectDir = $dir;
    }

    /**
     * Returns the path to parsers config file
     *
     * @return string
     * @throws \RuntimeException
     */
    protected function getParsersConfigPath()
    {
        if (null !== $this->parsersConfigPath) {
            return $this->parsersConfigPath;
        }

        $path = sprintf('%s/config/parsers.yml', $this->getProjectDir());

        if (!file_exists($path)) {
            throw new \RuntimeException(sprintf('Configuration file "%s" is not found&'));
        }

        return $this->parsersConfigPath = $path;
    }

    /**
     * Return the user parsers directory
     *
     * @return string|false
     */
    protected function getUserParsersDir()
    {
        $path = getenv('PARSERS_DIR');

        if ($path && is_dir($path)) {
            return $path;
        }

        $path = sprintf('%s/%s', $this->projectDir, 'Parsers');

        if (is_dir($path)) {
            return $path;
        }

        return false;
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
        $path = sprintf('%s/.env', $this->getProjectDir());

        if (!file_exists($path)) {
            throw new \RuntimeException('File .env not found in project');
        }

        $dotenv->load($path);
    }
}
