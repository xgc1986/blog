<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Paginator;

use Doctrine\ORM\QueryBuilder;

class Paginator
{
    // input
    protected $pageSize;

    /**
     * @var QueryBuilder
     */
    protected $query;

    // output
    protected $size;
    protected $pages;
    protected $page;
    protected $currentPage;

    function __construct(int $pageSize)
    {
        $this->pageSize = $pageSize;

        $this->size = 0;
        $this->pages = 0;
        $this->currentPage = 0;
        $this->page = [];
    }

    public function setQuery(QueryBuilder $query)
    {
        $this->query = $query;
    }

    public function execute(int $page = 0, array $order = [])
    {
        $this->currentPage = $page;
        $query = $this->query->getQuery();
        $query->setMaxResults($this->pageSize);
        $query->setFirstResult($this->pageSize * $page);

        $this->size = count(new \Doctrine\ORM\Tools\Pagination\Paginator($query));
        if ($this->size === 0) {
            $this->pages = 0;
        } else {
            $this->pages = (int)round($this->size / $this->pageSize) + 1;
        }
        $this->page = $query->getResult();
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

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function getPageSize(): int
    {
        return $this->pageSize;
    }
}
