<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Repository;

use Xgc\CoreBundle\Paginator\Paginator;

class EntityRepository extends \Doctrine\ORM\EntityRepository
{
    public function paginate(int $page, int $size, array $order): Paginator
    {
        $query = $this->createQueryBuilder('e')
            ->setMaxResults($size)
            ->setFirstResult($page * $size);
        $return = new Paginator($size);
        $return->setQuery($query);
        $return->execute($page);

        return $return;
    }
}
