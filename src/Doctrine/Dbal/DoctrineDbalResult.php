<?php

declare(strict_types = 1);

namespace Larium\Search\Doctrine\Dbal;

use PDO;
use Larium\Search\Result;
use Doctrine\DBAL\Query\QueryBuilder;
use function sprintf;
use function intval;
use function reset;

class DoctrineDbalResult implements Result
{
    private $queryBuilder;

    private $count;

    private $countField;

    private $countFunction;

    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    public function fetch(int $offset, int $limit): array
    {
        $this->queryBuilder->setFirstResult($offset);
        $this->queryBuilder->setMaxResults($limit);
        $stmt = $this->queryBuilder->executeQuery();

        return $stmt->fetchAllAssociative();
    }

    public function count(): int
    {
        if (null === $this->count) {
            $qb = clone $this->queryBuilder;
            if ($this->countFunction) {
                $qb = $this->countFunction->__invoke($qb);
            } else {
                $sql = sprintf('COUNT(%s) AS total_results', $this->getCountField());
                $qb->select($sql)
                   ->resetQueryPart('orderBy')
                   ->setMaxResults(1);
            }

            $this->count = intval($qb->executeQuery()->fetchOne());
        }

        return $this->count;
    }

    public function getCountField(): string
    {
        if (null === $this->countField) {
            return sprintf('%sid', $this->getAlias());
        }

        return $this->countField;
    }

    public function setCountField(string $countField): void
    {
        $this->countField = $countField;
    }

    public function setCountCallable(callable $function): void
    {
        $this->countFunction = $function;
    }

    private function getAlias(): string
    {
        $parts = $this->queryBuilder->getQueryParts();
        if (isset($parts['from'])) {
            $first = reset($parts['from']);

            return ($first['alias'] ?? $first['table']) . '.';
        }

        return '';
    }
}
