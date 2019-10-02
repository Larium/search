<?php

declare(strict_types = 1);

namespace Larium\Search\Doctrine\Dbal;

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

    public function __construct(QueryBuilder $queryBuilder, string $countField = null)
    {
        $this->queryBuilder = $queryBuilder;
        $this->countField = $countField;
    }

    public function fetch(int $offset, int $limit): array
    {
        $this->queryBuilder->setFirstResult($offset);
        $this->queryBuilder->setMaxResults($limit);
        $stmt = $this->queryBuilder->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function count()
    {
        if (null === $this->count) {
            $qb = clone $this->queryBuilder;
            $sql = sprintf('COUNT(%s) AS total_results', $this->getCountField());
            $qb->select($sql)
               ->resetQueryPart('orderBy')
               ->setMaxResults(1);
            $this->count = intval($qb->execute()->fetchColumn());
        }

        return $this->count;
    }

    private function getCountField(): string
    {
        if (null === $this->countField) {
            return sprintf('%sid', $this->getAlias());
        }

        return $this->countField;
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
