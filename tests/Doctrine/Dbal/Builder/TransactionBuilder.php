<?php

declare(strict_types = 1);

namespace Larium\Search\Doctrine\Dbal\Builder;

use Doctrine\DBAL\Query\QueryBuilder;
use Larium\Search\Criteria;
use Larium\Search\Doctrine\Dbal\Builder;

class TransactionBuilder implements Builder
{
    public function supports(Criteria $criteria): bool
    {
        return $criteria->resourceName === 'transactions';
    }

    public function build(Criteria $criteria, QueryBuilder $queryBuilder): void
    {
        $queryBuilder->select('*')
           ->from('transactions', 't');
    }
}
