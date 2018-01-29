<?php

namespace Ig0rbm\Webcrawler\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @package Ig0rbm\Webcrawler
 * @author 1g0rbm <m1g0rb89@gmail.com>
 */
class PredisCommand extends BaseParserConsole
{
    protected function configure()
    {
        $this
            ->setName('predis:info')
            ->setDescription('Show info about saved data in redis storage')
            ->setHelp('This command allows you to find out information about saved data in redis storage');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

    }
}
