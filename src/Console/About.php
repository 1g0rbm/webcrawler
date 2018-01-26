<?php

namespace Ig0rbm\Webcrawler\Console;

use Ig0rbm\Webcrawler\CrawlerKernel;
use Ig0rbm\Webcrawler\ParserKernel;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\OutputArgument;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Helper\TableSeparator;
use Predis\Connection\ConnectionException;

/**
 * @package Ig0rbm\Webcrawler
 * @author 1g0rbm <m1g0rb89@gmail.com>
 */
class About extends BaseParserConsole
{
    protected function configure()
    {
        $this
            ->setName('about')
            ->setDescription('Shows general information about the parser')
            ->setHelp('This command shows you the state of all the nodes of the framework and its readiness for parsing');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $rows = [
            ['<info>Webcrawler</info>'],
            new TableSeparator(),
            ['Version', CrawlerKernel::VERSION],
            new TableSeparator(),
            ['<info>Envinronment</info>'],
            new TableSeparator(),
            ['APP_ENV', getenv('ENV')]
        ];

        $io->table([], $rows);
    }

}
