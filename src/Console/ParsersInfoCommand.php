<?php

namespace Ig0rbm\Webcrawler\Console;

use Ig0rbm\Webcrawler\ParserKernel;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\OutputArgument;

/**
 * @package Ig0rbm\Webcrawler
 * @author 1g0rbm <m1g0rb89@gmail.com>
 */
class ParsersInfoCommand extends BaseParserConsole
{
    protected function configure()
    {
        $this
            ->setName('parser:info')
            ->setDescription('Show info about all registered parsers')
            ->setHelp('This command allows you to find out information about all regisered parsers')
            ->addArgument('parsername', InputArgument::OPTIONAL, 'Name of the parser');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getArgument('parsername')) {
            $this
                ->setCurrentParserByName($input->getArgument('parsername'))
                ->pushInfo();
        } else {
            $manager = $this;
            $this->parsers->walk(function($key, $parser) use($manager) {
                $manager->pushInfo($parser);
            });
        }

        $output->writeln($this->stdOut);
    }

    protected function pushInfo(ParserKernel $parser = null)
    {
        $parser = $parser ?: $this->currentParser;

        if (!$parser) {
            throw new InvalidArgumentException ('Parser instance is not passed.');
        }

        $this
            ->pushToStdOut(sprintf('<info>name:</info> %s', $parser->getName()))
            ->pushToStdOut(sprintf('<info>chain length:</info> %s', $parser->getChainLength()))
            ->pushToStdOut(sprintf('<info>request:</info> %s', $parser->getRequest() ? 'OK' : 'NOT'))
            ->pushToStdOut('')
            ->pushToStdOut(sprintf('<info>status:</info> %s', $parser->getStatusText()))
            ->pushToStdOut('');

        return $this;
    }
}
