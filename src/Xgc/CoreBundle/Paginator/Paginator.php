<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Paginator;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityRepository;

class Paginator
{
    // input
    protected $repository;
    protected $pageSize;

    /**
     * @var QueryBuilder
     */
    protected $query;

    // output
    protected $size;
    protected $pages;
    protected $page;

    function __construct(EntityRepository $repository, int $pageSize)
    {
        $this->repository = $repository;
        $this->pageSize = $pageSize;

        $this->size = 0;
        $this->pages = 0;
        $this->page = [];
    }

    public function setQuery(QueryBuilder $query)
    {
        $this->query = $query;
    }

    public function execute(int $page = 0, array $order = [])
    {
        $st = $this->query->execute();
        $this->size = $st->rowCount();
        $this->pages = round($this->size / $this->pageSize) + 1;
        $this->page = $st->fetchAll();
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getPage(): array
    {
        return $this->page;
    }

    public function getPages(): int
    {
        return $this->pages;
    }
}
