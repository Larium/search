<?php

declare(strict_types = 1);

namespace Larium\Search\Doctrine\Dbal;

use Doctrine\DBAL\Query\QueryBuilder;
use Larium\Search\Criteria;
use Larium\Search\Result;
use Larium\Search\SearchEngine;

class DoctrineDbalSearchEngine implements SearchEngine
{
    private $queryBuilder;

    private $builders = [];

    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    public function match(Criteria $criteria): Result
    {
        foreach ($this->builders as $builder) {
            if ($builder->supports($criteria)) {
                $builder->build($criteria, $this->queryBuilder);
            }
        }

        return new DoctrineDbalResult($this->queryBuilder);
    }

    public function add(Builder $builder): DoctrineDbalSearchEngine
    {
        $this->builders[] = $builder;

        return $this;
    }
}
