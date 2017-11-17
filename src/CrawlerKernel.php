<?php

namespace Ig0rbm\Webcrawler;

use Ig0rbm\HandyBox\HandyBoxContainer;
use Ig0rbm\Webcrawler\Box\ORMDoctrineBox;
use Ig0rbm\Webcrawler\Box\DoctrineConsoleRunnerBox;
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
    private $diContainer;

    /**
     * @var HandyBoxContainer
     */
    private $parsingChain;

    /**
     * The absolute path to project directory
     */
    private $projectDir;

    public function __construct()
    {
        $this->projectDir = $this->getProjectDir();

        $this->loadDotenv(new Dotenv());

        $this->containerInitialize();
        $this->consoleInitialize();
    }

    /**
     * Run application
     */
    public function run()
    {
        $this->console->run();
    }

    /**
     * Initialization default console command
     */
    protected function consoleInitialize()
    {
        $this->console = new Console();
        $this->console->setHelperSet($this->diContainer->fabricate('console_runner'));
        $this->console->add(new ParsingManager());
    }

    protected function containerInitialize()
    {
        $this->diContainer = new HandyBoxContainer();

        $this->diContainer->storage()->set('path_to_entities', sprintf('%s/Entity', $this->projectDir));
        $this->diContainer->register(new ORMDoctrineBox());
        $this->diContainer->register(new DoctrineConsoleRunnerBox());
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
