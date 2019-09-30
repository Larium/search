<?php

declare(strict_types = 1);

namespace Larium\Search\Doctrine\Dbal;

use Larium\Search\Result;
use Doctrine\DBAL\Query\QueryBuilder;

class DoctrineDbalResult implements Result
{
    private $queryBuilder;

    private $count;

    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
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
            $alias = $this->getAlias();
            $sql = sprintf('COUNT(%sid) AS total_results', $alias);
            $qb->select($sql)
               ->resetQueryPart('orderBy')
               ->setMaxResults(1);
            $this->count = intval($qb->execute()->fetchColumn());
        }

        return $this->count;
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
