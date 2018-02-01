<?php

namespace Ig0rbm\Webcrawler\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @package Ig0rbm\Webcrawler
 * @author 1g0rbm <m1g0rb89@gmail.com>
 */
class ParserPredisDelCommand extends BaseParserConsole
{
    protected function configure()
    {
        $this
            ->setName('parser:predis:del')
            ->setDescription('Command delete value from predis by key or all values')
            ->setHelp('This command allows you to find out information about saved data in redis storage')
            ->addArgument('key', InputArgument::OPTIONAL, 'Key for delete');;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $key = $input->getArgument('key');

        if (null === $key) {
            $key = $this->parserPredis->allKeys();
        }

        if (false == $this->parserPredis->exist($key)) {
            $this->pushToStdOut('Nothing to del');
        } else {
            $this->pushToStdOut("Delete keys: {$this->parserPredis->delete($key)}");
        }

        $output->writeln($this->stdOut);
    }
}
