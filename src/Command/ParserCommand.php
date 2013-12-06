<?php
namespace Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Parser\Attachment;

class ParserCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('parse')
            ->setDescription('Super parser')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $parser = new Attachment('attachments.txt');
        $parser->parse();


        foreach ($parser->get() as $hash => $ids) {
            $output->writeln(implode(' ', $ids));
        }
    }
}
