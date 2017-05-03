<?php
declare(strict_types=1);
namespace Xgc\SphinxBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SphinxUpdateCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('xgc:sphinx:update')
            ->setDescription('Updates sphinx');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $conf = $this->getContainer()->getParameter('xgc.sphinx.conf');
        $bin = $this->getContainer()->getParameter('xgc.sphinx.bin');

        $flags = "--all";
        if ($bin) {
            $command = "$bin/indexer --config $conf $flags --rotate";
        } else {
            $command = "indexer --config $conf $flags --rotate";
        }

        echo shell_exec($command);
    }
}
