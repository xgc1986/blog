<?php
declare(strict_types=1);
namespace Xgc\InfluxBundle\Command;

use AppBundle\Entity\Log;
use DateTimeZone;
use InfluxDB\Database;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Xgc\InfluxBundle\Annotation\MeasurementReader;
use Xgc\InfluxBundle\Influx\Point;
use Xgc\UtilsBundle\Helper\DateTime;

class InfluxTest2Command extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('xgc:influx:test2')
            ->setDescription('Hello PhpStorm');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $influx = $this->getContainer()->get('xgc.influx');

        dump($influx->read(Log::class, [], null, null, false, 1, 0));
    }
}
