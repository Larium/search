<?php

declare(strict_types = 1);

namespace Larium\Search\Doctrine\Dbal;

use Doctrine\DBAL\Query\QueryBuilder;
use Larium\Search\Criteria;

interface Builder
{
    /**
     * Checks if current builder supports given criteria.
     *
     * @param Criteria $criteria
     * @return bool
     */
    public function supports(Criteria $criteria): bool;

    /**
     * Apply criteria to given query builder.
     *
     * @param Criteria $criteria
     * @param QueryBuilder $queryBuilder
     * @return void
     */
    public function build(Criteria $criteria, QueryBuilder $queryBuilder): void;
}
