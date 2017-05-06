<?php
declare(strict_types=1);
namespace Xgc\InfluxBundle\DependencyInjection;

class Point extends \InfluxDB\Point
{
    public function __construct(
        $measurement,
        array $tags = [],
        array $additionalFields = [],
        $timestamp = null
    ) {
        parent::__construct($measurement, null, $tags, $additionalFields, $timestamp);
    }

    public function __construct2(/* INFLUX ENTITY */$model) {

    }
}
