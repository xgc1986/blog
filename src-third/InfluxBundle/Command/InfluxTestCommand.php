<?php

namespace Xgc\InfluxBundle\Command;

use InfluxDB\Database;
use InfluxDB\Point;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Xgc\UtilsBundle\Helper\DateTime;

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
        //$client = new \InfluxDB\Client('localhost', 8086);

        $user = $this->getContainer()->getParameter('xgc.influx.user');
        $pass = $this->getContainer()->getParameter('xgc.influx.pass');
        $host = $this->getContainer()->getParameter('xgc.influx.host');
        $port = $this->getContainer()->getParameter('xgc.influx.port');
        echo sprintf("influxdb://$user:$pass@%s:%s/%s", $host, $port, 'base_test') . PHP_EOL;
        echo sprintf('influxdb://user:pass@%s:%s/%s', 'localhost', 8086, 'base_test') . PHP_EOL;
        $database = \InfluxDB\Client::fromDSN(sprintf("influxdb://$user:$pass@%s:%s/%s", $host, $port, 'base_test'));

        /*$result = $database->query('select * from test_metric LIMIT 5');
        $points = $result->getPoints();*/

        $points = [
            new Point(
                'test_data', // name of  the measurement
                null, // the measurement value
                ['penis' => 'penepene'], // optional tags
                ['cpucount' => 15, 'sudosu' => true], // optional additional fields
                DateTime::fromFormat("U", (new DateTime())->getTime() - 1)->getTime()) // Time precision has to be set to seconds!
            ,
            new Point(
                'test_data', // name of the measurement
                null, // the measurement value
                ['penis' => 'penepene'], // optional tags
                ['cpucount' => 12, 'sudosu' => false], // optional additional fields
                (new DateTime())->getTime()) // Time precision has to be set to seconds!
        ];

// we are writing unix timestamps, which have a second precision
        $result = $database->writePoints($points, Database::PRECISION_SECONDS);
    }
}
