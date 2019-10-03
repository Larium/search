<?php

declare(strict_types = 1);

namespace Larium\Search\Criteria;

use function intval;

class Paginating
{
    const DEFAULT_CURRENT_PAGE = 1;

    const DEFAULT_ITEMS_PER_PAGE = 10;

    public $currentPage;

    public $itemsPerPage;

    public $offset;

    private function __construct(int $currentPage, int $itemsPerPage)
    {
        $this->currentPage = $currentPage;
        if ($this->currentPage <= 0) {
            $this->currentPage = self::DEFAULT_CURRENT_PAGE;
        }
        $this->itemsPerPage = $itemsPerPage;
        if ($this->itemsPerPage <= 0) {
            $this->itemsPerPage = self::DEFAULT_ITEMS_PER_PAGE;
        }
        $this->offset = $this->currentPage * $this->itemsPerPage - $this->itemsPerPage;
    }

    public static function fromQueryParams(array $queryParams = []): Paginating
    {
        $currentPage = intval($queryParams['page'] ?? self::DEFAULT_CURRENT_PAGE);

        $itemsPerPage = intval($queryParams['limit'] ?? self::DEFAULT_ITEMS_PER_PAGE);

        return new self($currentPage, $itemsPerPage);
    }
}
