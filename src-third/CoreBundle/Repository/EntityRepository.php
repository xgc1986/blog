<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use Xgc\CoreBundle\Paginator\Paginator;

class EntityRepository extends \Doctrine\ORM\EntityRepository
{
    public function paginate(int $page, int $size, array $order): Paginator
    {
        $key = 'e.' . array_keys($order)[0] ?? 'id';
        $by = $order[$key] ?? 'ASC';

        $query = $this->createQueryBuilder('e')
            ->orderBy($key, $by)
            ->setMaxResults($size)
            ->setFirstResult($page * $size);
        $return = new Paginator($size);
        $return->setQuery($query);
        $return->execute($page);

        return $return;
    }

    public function paginateQuery(int $page, int $size, QueryBuilder $query): Paginator
    {
        $return = new Paginator($size);
        $return->setQuery($query);
        $return->execute($page);
        return $return;
    }
}
