<?php
declare(strict_types=1);
namespace Xgc\InfluxBundle\Service;

use InfluxDB\Client;
use InfluxDB\Database;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Xgc\InfluxBundle\Annotation\MeasurementReader;
use Xgc\InfluxBundle\Entity\MeasurementEntity;
use Xgc\InfluxBundle\Influx\Paginator;
use Xgc\UtilsBundle\Helper\DateTime;

class InfluxService
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Database
     */
    protected $database;
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->connect();
    }

    public function connect()
    {
        if (!$this->client) {
            $user = $this->container->getParameter('xgc.influx.user');
            $pass = $this->container->getParameter('xgc.influx.pass');
            $host = $this->container->getParameter('xgc.influx.host');
            $port = $this->container->getParameter('xgc.influx.port');

            $this->client = new Client($host, $port, $user, $pass);
        }
    }

    public function connectDB(): Database
    {
        $this->connect();
        if (!$this->database) {
            $db = $this->container->getParameter('xgc.influx.database');
            $this->database = $this->client->selectDB($db);
        }
        return $this->database;
    }

    public function write(array $dataSet): void
    {
        $this->connectDB();

        $reader = new MeasurementReader();
        $points = [];
        foreach ($dataSet as $data) {
            $points[] = $reader->convert($data);
        }

        $this->database->writePoints($points, Database::PRECISION_MICROSECONDS);
    }

    public function read(
        string $influxEntity,
        array $tags = [],
        ?DateTime $from = null,
        ?DateTime $to = null,
        bool $asc = true,
        int $limit = 25,
        int $offset = 0
    ): Paginator {
        $this->connectDB();
        $reader = new MeasurementReader();
        $dummy = new $influxEntity();
        $measurement = $reader->getMeasurement($dummy);

        $count = $this->buildCount($measurement, $tags, $from, $to);
        $query = $this->buildQuery($measurement, $tags, $from, $to, $asc, $limit, $offset);

        $result = $this->query($query);
        $total = $this->query($count)[0]['total_value'] ?? 0;

        $ret = [];

        foreach ($result as $item) {
            $ent = new $influxEntity();
            $this->fill($ent, $item);
            $ret[] = $ent;
        }

        $pag = new Paginator($ret, $limit, $total, $offset * $limit);
        return $pag;
    }

    public function query(string $query, array $params = []): array
    {
        $this->connectDB();
        $result = $this->database->query($query);

        $ret = [];

        if (empty($result->getSeries())) {
            return $ret;
        }
        foreach ($result->getSeries()[0]['values'] as $line) {
            $row = [];

            foreach ($line as $idx => $value) {
                if ($result->getSeries()[0]['columns'][$idx] === 'time') {
                    $row['time'] = (new DateTime($value, New \DateTimeZone('UTC')))->setTimezone(new \DateTimeZone('Europe/Paris'));
                } else {
                    $row[$result->getSeries()[0]['columns'][$idx]] = $value;
                }
            }
            $ret[] = $row;
        }

        return $ret;
    }

    public function getDatabase(): Database
    {
        $this->connectDB();

        return $this->getDatabase();
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    private function buildQuery(
        string $measurement,
        array $tags = [],
        ?DateTime $from = null,
        ?DateTime $to = null,
        bool $asc = true,
        int $limit = 25,
        int $offset = 0
    ): string {
        $hasWhere = false;
        $needsAnd = false;
        $query = "SELECT * FROM $measurement";

        if (!empty($tags)) {
            foreach ($tags as $tag => $value) {

                if (!$hasWhere) {
                    $hasWhere = true;
                    $query .= " WHERE";
                }

                if ($needsAnd) {
                    $query .= " AND";
                }

                $query .= " $tag = '$value'";
                $needsAnd = true;
            }
        }

        if ($from) {
            if (!$hasWhere) {
                $hasWhere = true;
                $query .= " WHERE";
            }

            if ($needsAnd) {
                $query .= " AND";
            }

            $query .= " time >= '" . $from->format('Y-m-d H:i:s') . "'";

            $needsAnd = true;
        }

        if ($to) {
            if (!$hasWhere) {
                $query .= " WHERE";
            }

            if ($needsAnd) {
                $query .= " AND";
            }

            $query .= " time <= '" . $to->format('Y-m-d H:i:s') . "'";
        }

        $query .= (" ORDER BY time " . ($asc? 'ASC' : 'DESC'));
        $query .= " LIMIT $limit OFFSET $offset";

        return $query;
    }

    private function buildCount(
        string $measurement,
        array $tags = [],
        ?DateTime $from = null,
        ?DateTime $to = null
    ): string {
        $hasWhere = false;
        $needsAnd = false;
        $query = "SELECT COUNT(*) AS total FROM $measurement";

        if (!empty($tags)) {
            foreach ($tags as $tag => $value) {

                if (!$hasWhere) {
                    $hasWhere = true;
                    $query .= " WHERE";
                }

                if ($needsAnd) {
                    $query .= " AND";
                }

                $query .= " $tag = '$value'";
                $needsAnd = true;
            }
        }

        if ($from) {
            if (!$hasWhere) {
                $hasWhere = true;
                $query .= " WHERE";
            }

            if ($needsAnd) {
                $query .= " AND";
            }

            $query .= " time >= '" . $from->format('Y-m-d H:i:s') . "'";

            $needsAnd = true;
        }

        if ($to) {
            if (!$hasWhere) {
                $query .= " WHERE";
            }

            if ($needsAnd) {
                $query .= " AND";
            }

            $query .= " time <= '" . $to->format('Y-m-d H:i:s') . "'";
        }

        return $query;
    }

    private function fill(MeasurementEntity $entity, array $data): MeasurementEntity
    {

        foreach ($data as $idx => $value) {
            if ($idx === 'time') {
                $entity->setTimeStamp($value);
            } else {
                if (method_exists($entity,"set" . ucfirst($idx))) {
                    $entity->{ "set" . ucfirst($idx) }($value);
                }
            }
        }

        return $entity;
    }
}
