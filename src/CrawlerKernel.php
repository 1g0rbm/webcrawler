<?php

namespace Ig0rbm\Webcrawler;

use Ig0rbm\HandyBox\HandyBoxContainer;
use Ig0rbm\HandyBag\HandyBag;
use Ig0rbm\Webcrawler\Box\DBALDoctrineBox;
use Ig0rbm\Webcrawler\Box\HandyBagBox;
use Ig0rbm\Webcrawler\Box\DoctrineConsoleRunnerBox;
use Ig0rbm\Webcrawler\Box\PrettyCurlBox;
use Ig0rbm\Webcrawler\Box\DomCrawlerBox;
use Ig0rbm\Webcrawler\Box\ParserFactoryBox;
use Ig0rbm\Webcrawler\Box\UnitFactoryBox;
use Ig0rbm\Webcrawler\Box\PredisServiceBox;
use Ig0rbm\Webcrawler\Box\VanillaPredisServiceBox;
use Ig0rbm\Webcrawler\Box\TransliterationBox;
use Ig0rbm\Webcrawler\Console\ParsersInfoCommand;
use Ig0rbm\Webcrawler\Console\ParsingRunCommand;
use Ig0rbm\Webcrawler\Console\AboutCommand;
use Ig0rbm\Webcrawler\Console\ParserPredisDelCommand;
use Symfony\Component\Console\Application as Console;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Yaml\Yaml;

/**
 * @package Ig0rbm\Webcrawler
 * @author 1g0rbm <m1g0rb89@gmail.com>
 */
class CrawlerKernel
{
    const VERSION = '0.0.1';

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
     * The current environment
     *
     * @var string
     */
    protected $environment;

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
        $this->container->storage()->set('parsers.config', $config);

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
     * @throws \Exception
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

        $this->console->add(new AboutCommand($this->container->get('parser.predis'), $this->parsers));
        $this->console->add(new ParserPredisDelCommand($this->container->get('parser.predis'), $this->parsers));

        // command for parsers
        $this->console->add(new ParsersInfoCommand($this->container->get('parser.predis'), $this->parsers));
        $this->console->add(new ParsingRunCommand($this->container->get('parser.predis'), $this->parsers));
    }

    /**
     * Initialization default dependencies
     *
     * @return void
     */
    protected function containerInitialize()
    {
        $this->container = new HandyBoxContainer();

        $storage = $this->container->storage();

        $storage->set('path_to.project_dir', $this->getProjectDir());
        $storage->set('path_to.user_parsers', $this->getUserParsersDir());
        $storage->set('path_to.var_dir', sprintf('%s/var', $this->getProjectDir()));
        $storage->set('path_to.entities', sprintf('%s/Entity', $this->getProjectDir()));
        $storage->set('path_to.proxy_files', sprintf('%s/var/proxy', $this->getProjectDir()));

        $this->container->register(new DBALDoctrineBox());
        $this->container->register(new DoctrineConsoleRunnerBox());
        $this->container->register(new VanillaPredisServiceBox());
        $this->container->register(new PredisServiceBox());
        $this->container->register(new PrettyCurlBox());
        $this->container->register(new DomCrawlerBox());
        $this->container->register(new HandyBagBox());
        $this->container->register(new ParserFactoryBox());
        $this->container->register(new UnitFactoryBox());
        $this->container->register(new TransliterationBox());
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
