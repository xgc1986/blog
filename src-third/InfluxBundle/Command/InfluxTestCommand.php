<?php
declare(strict_types=1);
namespace Xgc\InfluxBundle\Command;

use AppBundle\Entity\Log;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InfluxTestCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('xgc:influx:test')
            ->setDescription('Hello PhpStorm');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $influx = $this->getContainer()->get('xgc.influx');

        $length = 10;
        $ps = [];

        for ($i = 0; $i < $length; $i++) {
            $ps[] = new Log();
        }
        for ($i = 0; $i < $length; $i++) {
            $ps[$i]->setLevel("warning");
            $ps[$i]->setUsername('d');
            $ps[$i]->setTag('warning_log');
            $ps[$i]->setMessage('testing_logs');
        }

        $influx->write($ps);
    }
}
