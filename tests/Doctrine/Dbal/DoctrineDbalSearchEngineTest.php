<?php

declare(strict_types = 1);

namespace Larium\Search\Doctrine\Dbal;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\Expression\ExpressionBuilder;
use Doctrine\DBAL\Query\QueryBuilder;
use Larium\Search\Criteria;
use Larium\Search\Doctrine\Dbal\Builder\TransactionBuilder;
use Larium\Search\Doctrine\Dbal\Builder\TransactionStatusBuilder;
use Larium\Search\Result;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DoctrineDbalSearchEngineTest extends TestCase
{
    private Connection|MockObject $conn;

    public function setUp(): void
    {
        $this->conn = $this->createMock(Connection::class);

        $expressionBuilder = new ExpressionBuilder($this->conn);
        $this->conn->expects($this->any())
                   ->method('getExpressionBuilder')
                   ->will($this->returnValue($expressionBuilder));
    }

    public function testShouldMatchCriteria()
    {
        $qb = new QueryBuilder($this->conn);

        $engine = new DoctrineDbalSearchEngine($qb);
        $engine->add(new TransactionBuilder())
               ->add(new TransactionStatusBuilder());

        $c = Criteria::fromQueryParams('transactions', ['status' => 'paid']);

        $result = $engine->match($c);

        $this->assertInstanceOf(Result::class, $result);

        $this->assertEquals(
            'SELECT * FROM transactions t WHERE t.status = ?',
            (string) $qb
        );
    }
}
