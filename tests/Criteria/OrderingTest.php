<?php

declare(strict_types = 1);

namespace Larium\Search\Criteria;

use PHPUnit\Framework\TestCase;

class OrderingTest extends TestCase
{
    public function testShouldCreateASCOrdering(): void
    {
        $o = Ordering::fromQueryParams(['sort' => 'createdAt']);

        $this->assertEquals('createdAt', $o->field);
        $this->assertEquals(Ordering::ASC, $o->direction); 
    }

    public function testShouldCreateDESCOrdering(): void
    {
        $o = Ordering::fromQueryParams(['sort' => '-createdAt']);

        $this->assertEquals('createdAt', $o->field);
        $this->assertEquals(Ordering::DESC, $o->direction); 
    }
}