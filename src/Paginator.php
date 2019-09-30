<?php

declare(strict_types = 1);

namespace Larium\Search;

use Countable;
use IteratorAggregate;

interface Paginator extends Countable, IteratorAggregate
{
    public function getTotalPages(): int;

    public function getCurrentPage(): int;

    public function hasMore(): bool;

    public function getNextPage(): ?int;

    public function getPreviousPage(): ?int;
}
