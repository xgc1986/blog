<?php
declare(strict_types=1);
namespace Xgc\SphinxBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Xgc\SphinxBundle\Service\SphinxServerService;

class SphinxStatusCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('xgc:sphinx:status')
            ->setDescription('check sphinx status');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $out = shell_exec("ps -A | grep 'searchd'");

        if (count(explode("\n", $out)) < 4) {
            echo "Sphinx is NOT running\n";
        } else {
            echo "Sphinx is running\n";
        }
    }
}
