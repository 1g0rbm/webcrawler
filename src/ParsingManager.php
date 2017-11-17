<?php

namespace Ig0rbm\Webcrawler;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @package Ig0rbm\Webcrawler
 * @author 1g0rbm <m1g0rb89@gmail.com>
 */
class ParsingManager extends Command
{
    private $diContainer;

    protected function configure()
    {
        $this
            ->setName('unit:create')
            ->setDescription('Creates a new unit to parsing chain')
            ->setHelp('This command allows you to create a unit to parsing chain');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Parsing Manager',
            '===============',
            ''
        ]);
    }
}