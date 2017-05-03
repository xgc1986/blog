<?php
declare(strict_types=1);
namespace Xgc\SphinxBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SphinxStartCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('xgc:sphinx:start')
            ->setDescription('Starts sphinx');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $conf = $this->getContainer()->getParameter('xgc.sphinx.conf');
        $bin = $this->getContainer()->getParameter('xgc.sphinx.bin');

        if ($bin) {
            $command = "$bin/searchd -c $conf";
        } else {
            $command = "searchd -c $conf";
        }

        echo shell_exec($command);
    }
}
