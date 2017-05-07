<?php
declare(strict_types=1);
namespace Xgc\InfluxBundle\Influx;

class Paginator
{
    protected $pageSize;
    protected $size;
    protected $pages;
    protected $page;
    protected $currentPage;
    protected $hasPrevious;
    protected $hasNext;

    public function __construct(array $data, int $pageSize, int $size, int $page)
    {
        $this->pageSize = 25;
        $this->size = $size;
        if ($this->size === 0) {
            $this->pages = 0;
        } else {
            $this->pages = (int)round($this->size / $this->pageSize) + 1;
        }
        $this->page = $data;
        $this->currentPage = max(0, min($page, $this->pages - 1));
        $this->hasNext = $this->currentPage < $this->pages - 1;
        $this->hasPrevious = $this->currentPage > 0;
    }

    /**
     * @return int
     */
    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    /**
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * @return array
     */
    public function getPage(): array
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @return bool
     */
    public function isHasNext(): bool
    {
        return $this->hasNext;
    }

    /**
     * @return bool
     */
    public function isHasPrevious(): bool
    {
        return $this->hasPrevious;
    }
}
