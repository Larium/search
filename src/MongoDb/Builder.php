<?php

declare(strict_types=1);

namespace Larium\Search\MongoDb;

use Larium\Search\Criteria;

interface Builder
{
    /**
     * Checks if current builder supports given criteria.
     */
    public function supports(Criteria $criteria): bool;

    /**
     * Apply criteria to given filter builder.
     */
    public function build(Criteria $criteria, FilterBuilder $filterBuilder): void;
}
