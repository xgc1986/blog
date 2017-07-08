<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Xgc\CoreBundle\Entity\Entity;
use Xgc\CoreBundle\Exception\Http\PreconditionFailedException;
use Xgc\UtilsBundle\Helper\JSON;

/**
 * Class Doctrine
 * @package Nakima\CoreBundle\Service
 */
class Doctrine extends Registry
{
    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * Construct.
     *
     * @param ContainerInterface $container
     * @param array $connections
     * @param array $entityManagers
     * @param string $defaultConnection
     * @param string $defaultEntityManager
     */
    public function __construct(
        ContainerInterface $container,
        array $connections,
        array $entityManagers,
        $defaultConnection,
        $defaultEntityManager
    ) {
        parent::__construct($container, $connections, $entityManagers, $defaultConnection, $defaultEntityManager);
    }

    /**
     * @param Entity[]|Entity $entities
     */
    public function persist($entities): void
    {
        if (!is_array($entities)) {
            $entities = [$entities];
        }

        $validator = $this->loadValidator();

        foreach ($entities as $entity) {
            if ($entity) {
                $errors = $validator->validate($entity);

                if (count($errors) > 0) {
                    throw new PreconditionFailedException(
                        $errors[0]->getMessage(),
                        ['param' => $errors[0]->getPropertyPath()]
                    );
                }
                $this->getManager()->persist($entity);
            }
        }
    }

    /**
     * @return ValidatorInterface
     */
    private function loadValidator(): ValidatorInterface
    {
        $this->validator = $this->validator ?? $this->container->get('validator');

        return $this->validator;
    }

    /**
     * @param Entity[]|Entity $entities
     */
    public function remove($entities): void
    {
        if (!is_array($entities)) {
            $entities = [$entities];
        }

        foreach ($entities as $entity) {
            if ($entity) {
                $this->validator->validate($entity);
                $this->getManager()->remove($entity);
            }
        }
    }

    /**
     * @param Entity[]|Entity $persists
     * @param Entity[]|Entity $deletes
     */
    public function flush($persists = [], $deletes = []): void
    {
        $this->persist($persists);
        $this->remove($deletes);
        $this->getManager()->flush();
    }

    /**
     * @param $input
     * @param array $result
     * @param string $key
     * @return mixed
     */
    public function toArray($input, array &$result = [], string $key)
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
