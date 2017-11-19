<?php

namespace Ig0rbm\Webcrawler;

use Ig0rbm\HandyBag\HandyBag;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
            ->setHelp('This command allows you to find out information about all regisered parsers.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $writeln = [
            'ParserManager',
            '============='
        ];

        foreach ($this->parsers->getAll() as $name => $value) {
            $writeln[] = sprintf('name: %s', $value->getName());
            $writeln[] = sprintf('status: %s', $value->getStatus());
            $writeln[] = sprintf('=============');
        }

        $output->writeln($writeln);
    }
}
