<?php

namespace Ig0rbm\Webcrawler\Console;

use Ig0rbm\Webcrawler\Exception\RunNotReadyParserException;
use Ig0rbm\Webcrawler\ParserKernel;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Ig0rbm\Webcrawler\ErrorHandler;

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
            ->addArgument('parsername', InputArgument::REQUIRED, 'Name of the parser')
            ->addArgument('unit_num', InputArgument::OPTIONAL, 'Num of the chain unit');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $name = $input->getArgument('parsername');
            $number = $input->getArgument('unit_num');

            $this->setCurrentParserByName($name);

            if ($this->currentParser->getStatus() !== ParserKernel::READY) {
                //TODO make exception for it
                throw new \Exception(sprintf('Unable start parsing process for "%s" status', $this->currentParser->getStatusText()));
            }

            $this
                ->currentParser
                ->setBefore(function ($stepName) use ($output) {
                    $output->writeln([
                        "Start parsing for \"$stepName\"",
                        '==============================='
                    ]);
                })
                ->setAfter(function ($stepName) use ($output) {
                    $output->writeln([
                        "Stop parsing for \"$stepName\"",
                        '==============================='
                    ]);
                })
                ->setDuring(function (array $logs) use ($output) {
                    $output->writeln($logs);
                })
                ->run($number);
        } catch (RunNotReadyParserException $e) {
            $this->pushToStdOut('<error>' . $e->getMessage() . '</error>');
        } catch (\Exception $e) {
            (new ErrorHandler($e))->handle();
        }

        $output->writeln($this->stdOut);
    }
}
