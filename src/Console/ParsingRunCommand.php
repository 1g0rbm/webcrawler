<?php

namespace Ig0rbm\Webcrawler\Console;

use Ig0rbm\Webcrawler\ParserKernel;
use Ig0rbm\HandyBag\HandyBag;
use Ig0rbm\Webcrawler\Exception\NotFoundException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\OutputArgument;

/**
 * @package Ig0rbm\Webcrawler
 * @author 1g0rbm <m1g0rb89@gmail.com>
 */
class ParsingRunCommand extends BaseParserConsole
{
    protected function configure()
    {
        $this
            ->setName('parser:run')
            ->setDescription('Start the parsing process')
            ->setHelp('This command starts the parsing process')
            ->addArgument('parsername', InputArgument::REQUIRED, 'Name of the parser');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('parsername');

        $this->setCurrentParserByName($name);

        if ($this->currentParser->getStatus() !== ParserKernel::READY) {
            //TODO make exception for it
            throw new \Exception(sprintf('Unable start parsing process for "%s" status', $this->currentParser->getStatusText()));
        }

        $this->currentParser->run();

        $output->writeln($this->stdOut);
    }
}
