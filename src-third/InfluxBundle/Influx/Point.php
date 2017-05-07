<?php
declare(strict_types=1);
namespace Xgc\InfluxBundle\Influx;

use Xgc\UtilsBundle\Helper\DateTime;

class Point extends \InfluxDB\Point
{
    public function __construct(
        string $measurement,
        array $tags = [],
        array $fields = [],
        DateTime $timestamp = null
    ) {
        if (empty($fields)) {
            parent::__construct($measurement, 1, $tags, [], $timestamp->getMicros());
        } else {
            parent::__construct($measurement, null, $tags, $fields, $timestamp->getMicros());
        }
    }
}
