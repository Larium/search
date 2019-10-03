<?php

declare(strict_types = 1);

namespace Larium\Search\Criteria;

use PHPUnit\Framework\TestCase;

class PaginatingTest extends TestCase
{
    public function testShouldCreatePaginating(): void
    {
        $paginating = Paginating::fromQueryParams(['page' => -1, 'limit' => 0]);

        $this->assertEquals(Paginating::DEFAULT_CURRENT_PAGE, $paginating->currentPage);
        $this->assertEquals(Paginating::DEFAULT_ITEMS_PER_PAGE, $paginating->itemsPerPage);
    }
}