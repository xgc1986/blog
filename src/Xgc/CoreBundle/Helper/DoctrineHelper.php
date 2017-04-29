<?php
declare(strict_types = 1);
namespace Xgc\CoreBundle\Helper;

use Xgc\CoreBundle\Entity\Entity;

class DoctrineHelper
{
    private static $instance;
    protected $map;

    public static function getInstance(): DoctrineHelper
    {
        self::$instance = self::$instance ?? new DoctrineHelper;
        return self::$instance;
    }

    private function __construct()
    {
        $this->map = [];
    }

    public function toArray($entity)
    {
        if ($entity === null) {
            return null;
        }
        if ($entity instanceof Entity) {
            $class = get_class($entity);
            $id = $entity->getId();

            $this->map[$class] = $this->map[$class] ?? [];
            if (isset($map[$class][$id])) {
                $ret = $entity->getId();
            } else {
                $this->map[$class][$id] = true;
                $ret = $entity->__toArray();
                ksort($ret);
            }
            $this->map[$class][$id] = $this->map[$class] ?? [];

            unset($this->map[$class][$id]);

            return $ret;
        } else if (is_array($entity)) {
            $ret = [];
            foreach ($entity as $key => $item) {
                $ret[$key] = $this->toArray($item);
            }
            ksort($ret);
            return $ret;
        } else if ($entity instanceof \stdClass) {
            $ret = [];
            foreach ($entity as $key => $item) {
                $ret[$key] = $this->toArray($item);

            }
            ksort($ret);
            return $ret;
        }

        return $entity;
    }
}
