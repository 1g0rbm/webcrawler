<?php

namespace Ig0rbm\Webcrawler\Console;

use Ig0rbm\Webcrawler\ParserKernel;
use Ig0rbm\HandyBag\HandyBag;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\OutputArgument;

/**
 * @package Ig0rbm\Webcrawler
 * @author 1g0rbm <m1g0rb89@gmail.com>
 */
class ParsingManager extends Command
{
    /**
     * Collection of ParserKernel inheritor
     *
     * @var HandyBag
     */
    private $parsers;

    public function __construct(HandyBag $parsers)
    {
        $this->parsers = $parsers;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('parser:info')
            ->setDescription('Show info about all registered parsers.')
            ->setHelp('This command allows you to find out information about all regisered parsers.')
            ->addArgument('parsername', InputArgument::OPTIONAL, 'Name of the parser');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $stdOut = [
            '<comment>ParserManager</comment>',
            '<comment>=============</comment>',
            ''
        ];

        if ($input->getArgument('parsername')) {
            $stdOut = $this->statsByName($input->getArgument('parsername'), $stdOut);
        } else {
            $stdOut = $this->allStats($stdOut);
        }

        $output->writeln($stdOut);
    }

    protected function statsByName(string $name, array $stdOut)
    {
        if (!$this->parsers->has($name)) {
            $stdOut[] = sprintf('<error>Parser with name "%s" is not registered.</error>', $name);
            $stdOut[] = '';
        } else {
            $stdOut = $this->getInfo($stdOut, $this->parsers->get($name));
        }

        return $stdOut;
    }

    /**
     * @param array $stdOut
     * @return array
     */
    protected function allStats(array $stdOut)
    {
        foreach ($this->parsers->getAll() as $parser) {
            $stdOut = $this->getInfo($stdOut, $parser);
        }

        return $stdOut;
    }

    protected function getInfo(array $stdOut, ParserKernel $parser)
    {
        $stdOut[] = sprintf('<info>name:</info> %s', $parser->getName());
        $stdOut[] = sprintf('<info>chain length:</info> %s', $parser->getChainLength());
        $stdOut[] = sprintf('<info>request:</info> %s', $parser->getRequest() ? 'OK' : 'NOT');
        $stdOut[] = '';
        $stdOut[] = sprintf('<info>status:</info> %s', $parser->getStatusText());
        $stdOut[] = sprintf('<comment>=============</comment>');

        return $stdOut;
    }
}
