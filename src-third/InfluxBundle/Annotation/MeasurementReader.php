<?php
declare(strict_types=1);
namespace Xgc\InfluxBundle\Annotation;

use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionObject;
use ReflectionProperty;
use Xgc\InfluxBundle\Entity\MeasurementEntity;
use Xgc\InfluxBundle\Influx\Point;

class MeasurementReader
{

    /**
     * @var AnnotationReader
     */
    private $reader;

    /**
     * @var ReflectionObject
     */
    private $reflectionObject;

    /**
     * @var Measurement
     */
    private $classAnnotation;

    /**
     * @var ReflectionProperty[]
     */
    private $reflectionProperties;

    public function __construct()
    {
        $this->reader = new AnnotationReader();
    }

    public function getMeasurement(MeasurementEntity $line): string
    {
        $this->loadClassAnnotation($line);
        return $this->classAnnotation->getPropertyName();
    }

    public function getFields(MeasurementEntity $line): array
    {
        $tags = [];
        foreach ($this->loadReflectionProperties($line) as $reflectionProperty) {
            $tag = $this->reader->getPropertyAnnotation($reflectionProperty, Tag::class);
            if ($tag) {
                $tags[] = $reflectionProperty->name;
            }
        }

        return $tags;
    }

    public function getTags(MeasurementEntity $line): array
    {
        $fields = [];
        foreach ($this->loadReflectionProperties($line) as $reflectionProperty) {
            $field = $this->reader->getPropertyAnnotation($reflectionProperty, Field::class);
            if ($field) {
                $fields[] = $reflectionProperty->name;
            }
        }

        return $fields;
    }

    public function convert(MeasurementEntity $line): ?Point
    {
        $this->loadClassAnnotation($line);
        $measurement = $this->getMeasurement($line);

        $tags = [];
        $fields = [];

        foreach ($this->loadReflectionProperties($line) as $reflectionProperty) {

            $tag = $this->reader->getPropertyAnnotation($reflectionProperty, Tag::class);
            $field = $this->reader->getPropertyAnnotation($reflectionProperty, Field::class);


            if ($tag) {
                $tags[$reflectionProperty->name] = $line->{'get' . ucfirst($reflectionProperty->name)}();
            } else if ($field) {
                $fields[$reflectionProperty->name] = $line->{'get' . ucfirst($reflectionProperty->name)}();
            }
        }

        $point = new Point($measurement, $tags, $fields, $line->getTimeStamp());

        return $point;
    }

    private function loadReflectionObject(MeasurementEntity $obj): ReflectionObject
    {
        if (!$this->reflectionObject) {
            $this->reflectionObject = new ReflectionObject($obj);
        }
        return $this->reflectionObject;
    }

    private function loadClassAnnotation(MeasurementEntity $obj): Measurement
    {
        if (!$this->classAnnotation) {
            $this->loadReflectionObject($obj);
            $this->classAnnotation = $this->reader->getClassAnnotation($this->reflectionObject, Measurement::class);
        }

        return $this->classAnnotation;
    }

    /**
     * @param MeasurementEntity $obj
     * @return ReflectionProperty[]
     */
    private function loadReflectionProperties(MeasurementEntity $obj): array
    {
        if (!$this->reflectionProperties) {
            $this->loadReflectionObject($obj);
            $this->reflectionProperties = $this->reflectionObject->getProperties();
        }

        return $this->reflectionProperties;
    }
}
