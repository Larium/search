<?php

declare(strict_types = 1);

namespace Larium\Search\Doctrine\Dbal;

use Larium\Search\Result;
use PHPUnit\Framework\TestCase;
use ArrayIterator;
use Larium\Search\Criteria\Paginating;
use Larium\Search\ResultPaginator;

class DoctrineDbalPaginatorTest extends TestCase
{
    private $result;

    public function setUp(): void
    {
        $this->result = $this->createMock(Result::class);
        $this->result->expects($this->any())->method('count')->willReturn(20);
        $this->result->expects($this->any())->method('fetch')->willReturn([
            ['id' => 1, 'amount' => 1000],
            ['id' => 2, 'amount' => 4500],
            ['id' => 3, 'amount' => 3000],
            ['id' => 4, 'amount' => 6000],
            ['id' => 5, 'amount' => 7000]
        ]);
    }

    public function testPaginator(): void
    {
        $paginating = Paginating::fromQueryParams(['page' => 1, 'limit' => 5]);
        $p = new ResultPaginator($this->result, $paginating);
        $iterator = $p->getIterator();

        $this->assertEquals(1, $p->getCurrentPage());
        $this->assertEquals(4, $p->getTotalPages());
        $this->assertTrue($p->hasMore());
        $this->assertEquals(2, $p->getNextPage());
        $this->assertNull($p->getPreviousPage());
        $this->assertInstanceOf(ArrayIterator::class, $iterator);

        $paginating = Paginating::fromQueryParams(['page' => 2, 'limit' => 5]);
        $p = new ResultPaginator($this->result, $paginating);
        $this->assertEquals(2, $p->getCurrentPage());
        $this->assertEquals(4, $p->getTotalPages());
        $this->assertTrue($p->hasMore());
        $this->assertEquals(3, $p->getNextPage());
        $this->assertEquals(1, $p->getPreviousPage());
    }
}
