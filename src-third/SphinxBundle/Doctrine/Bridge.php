<?php
declare(strict_types=1);
namespace Xgc\SphinxBundle\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;

/**
 * TODO
 * - instead of container
 *      - Registry (Doctrine)
 */
class Bridge implements BridgeInterface
{

    protected $doctrine;
    protected $em;
    protected $indexes;

    public function __construct(Registry $doctrine, array $indexes)
    {
        $this->doctrine = $doctrine;
        $this->indexes = $indexes;
    }

    public function getEntityManager(): EntityManager
    {
        if ($this->em === null) {
            $this->em = $this->doctrine->getManager();
        }

        return $this->em;
    }

    public function setEntityManager(EntityManager $em): void
    {
        if ($this->em !== null) {
            throw new \LogicException('Entity manager can only be set before any results are fetched');
        }

        $this->em = $em;
    }

    public function parseResults(array $results, string $index): array
    {
        if (!empty($results['error'])) {
            throw new \LogicException('Search completed with errors');
        }

        if (is_string($index)) {
            if (!isset($this->indexes[$index])) {
                throw new \InvalidArgumentException('Unknown index name: ' . $index);
            }
        } else if (is_array($index)) {
            foreach ($index as $idx) {
                if (!isset($this->indexes[$idx])) {
                    throw new \InvalidArgumentException('Unknown index name: ' . $idx);
                }
            }
        }

        if (empty($results['matches'])) {
            return $results;
        }

        $dbQueries = array_reverse(array_keys($this->indexes));

        foreach ($results['matches'] as $id => &$match) {
            $match['entity'] = false;

            if (is_string($index)) {
                $dbQueries[$index][] = $id;
            } else if (is_array(
                    $index
                ) && isset($match['attrs']['index_name']) && isset($this->indexes[$match['attrs']['index_name']])
            ) {
                $dbQueries[$match['attrs']['index_name']][] = $id;
            }
        }

        foreach ($dbQueries as $index => $ids) {
            if (!isset($this->indexes[$index])) {
                continue;
            }

            $entities = $this->getEntityManager()->getRepository($this->indexes[$index])->findBy(['id' => $ids]);

            foreach ($ids as $id) {
                $results['matches'][$id]['entity'] = $this->getEntityManager()->getRepository(
                    $this->indexes[$index]
                )->find($id);
            }
        }

        return $results;
    }
}
