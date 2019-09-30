<?php

declare(strict_types = 1);

namespace Larium\Search\Doctrine\Dbal;

use Doctrine\DBAL\Connection;
use Larium\Search\Criteria;
use Larium\Search\Result;
use Larium\Search\SearchEngine;

class DoctrineDbalSearchEngine implements SearchEngine
{
    private $connection;

    private $builders = [];

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function match(Criteria $criteria): Result
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        foreach ($this->builders as $builder) {
            if ($builder->supports($criteria)) {
                $builder->build($criteria, $queryBuilder);
            }
        }

        return new DoctrineDbalResult($queryBuilder);
    }

    public function add(Builder $builder): DoctrineDbalSearchEngine
    {
        $this->builders[] = $builder;

        return $this;
    }
}
