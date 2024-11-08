<?php

declare(strict_types=1);

namespace Larium\Search;

use Traversable;
use ArrayIterator;
use Larium\Search\Result;
use Larium\Search\Paginator;
use Larium\Search\Criteria\Paginating;

use function ceil;
use function intval;

class ResultPaginator implements Paginator
{
    private $result;

    private $paginating;

    private $iterator;

    private $count;

    public function __construct(Result $result, Paginating $paginating)
    {
        $this->result = $result;
        $this->paginating = $paginating;
        $this->iterator = new ArrayIterator();
    }

    public function count(): int
    {
        if ($this->count === null) {
            $this->count = $this->result->count();
        }

        return $this->count;
    }

    public function getIterator(): Traversable
    {
        if ($this->count() > 0 && $this->iterator->count() === 0) {
            $results = $this->result->fetch(
                $this->paginating->offset,
                $this->paginating->itemsPerPage
            );
            $this->iterator = new ArrayIterator($results);
        }

        return $this->iterator;
    }

    public function getTotalPages(): int
    {
        return intval(ceil($this->result->count() / $this->paginating->itemsPerPage));
    }

    public function getCurrentPage(): int
    {
        return $this->paginating->currentPage;
    }

    public function hasMore(): bool
    {
        return $this->paginating->currentPage < $this->getTotalPages();
    }

    public function getNextPage(): ?int
    {
        return $this->hasMore()
            ? $this->paginating->currentPage + 1
            : null;
    }

    public function getPreviousPage(): ?int
    {
        return $this->paginating->currentPage > 1
            ? $this->paginating->currentPage - 1
            : null;
    }
}
