<?php
declare(strict_types=1);
namespace Xgc\UtilsBundle\Helper;

class JsonHelper
{
    private static $instance;
    protected      $map;

    public static function getInstance(): JsonHelper
    {
        self::$instance = self::$instance ?? new JsonHelper();

        return self::$instance;
    }

    private function __construct()
    {
    }

    /**
     * @param JSON $input
     * @param array $result
     * @param string $key
     * @return mixed
     */
    public function encode($input, array &$result = [], string $key)
    {
        $result['__included'] = $result['__included'] ?? [];

        if ($input === null) {
            $result[$key] = null;
        } else if ($input instanceof \DateTime) {
            $result[$key] = $input->format('U');
        } else if ($input instanceof JSON) {
            $id   = $input->getId();
            $type = $input->__getType();
            $json = $input->__toArray();

            $result[$key]                     = $json;
            $result['__included'][$type]      = $result['__included'][$type] ?? [];
            $result['__included'][$type][$id] = $json;
            $json["__type"]                   = $type;
            $json["__id"]                     = $id;

            foreach ($json as $idx => $value) {
                $result[$key][$idx]                     = $this->encodeRecursive($value, $result, true);
                $result['__included'][$type][$id][$idx] = $result[$key][$idx];
            }
        } else if (is_array($input)) {
            foreach ($input as $value) {
                $result[$key]   = [];
                $result[$key][] = $this->encodeRecursive($value, $result, false);
            }
        } else if ($input instanceof \stdClass) {
            $result[$key] = [];
            foreach ($input as $idx => $value) {
                $result[$key][$idx] = $this->encodeRecursive($value, $result, true);
            }
        } else {
            $result[$key] = $input;
        }

        return $result;
    }

    private function encodeRecursive($input, array &$result = [], bool $createInclude)
    {
        if ($input === null) {
            return null;
        } else if ($input instanceof \DateTime) {
            return $input->format('U');
        } else if ($input instanceof JSON) {

            $id   = $input->getId();
            $type = $input->__getType();

            $result['__included'][$type]        = $result['__included'][$type] ?? [];
            $parsed                             = $result['__included'][$type][$id] ?? false;
            $result['__included'][$type]["$id"] = $result['__included'][$type][$id] ?? $input->__toArray();
            $json                               = $result['__included'][$type][$id];

            if ($createInclude) {
                $ret                                        = [
                    '__type' => $type,
                    '__id'   => $id,
                ];
                $result['__included'][$type][$id]["__type"] = $type;
                $result['__included'][$type][$id]["__id"]   = $id;
            } else {
                $ret                                        = $json;
                $json["__type"]                             = $type;
                $json["__id"]                               = $id;
                $result['__included'][$type][$id]["__type"] = $type;
                $result['__included'][$type][$id]["__id"]   = $id;
            }

            if (!$parsed) {
                foreach ($json as $key => $value) {
                    $json[$key]                             = $this->encodeRecursive($value, $result, true);
                    $result['__included'][$type][$id][$key] = $json[$key];
                }
            }

            return $ret;
        } else if (is_array($input)) {
            $ret = [];
            foreach ($input as $value) {
                $ret[] = $this->encodeRecursive($value, $result, $createInclude);
            }

            return $ret;
        } else if ($input instanceof \stdClass) {
            $ret = [];
            foreach ($input as $idx => $value) {
                $ret["$idx"] = $this->encodeRecursive($value, $result, true);
            }

            return $ret;
        }

        return $input;
    }
}
