<?php

declare(strict_types = 1);

namespace Larium\Search\Doctrine\Dbal\Builder;

use Doctrine\DBAL\Query\QueryBuilder;
use Larium\Search\Criteria;
use Larium\Search\Doctrine\Dbal\Builder;

class TransactionStatusBuilder implements Builder
{
    public function supports(Criteria $criteria): bool
    {
        return $criteria->resourceName === 'transactions'
            && isset($criteria->filtering->fields['status']);
    }

    public function build(Criteria $criteria, QueryBuilder $queryBuilder): void
    {
        $queryBuilder->where('t.status = ?')
                     ->setParameter(0, $criteria->filtering->fields['status']);
    }
}
