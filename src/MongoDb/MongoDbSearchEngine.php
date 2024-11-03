<?php

declare(strict_types=1);

namespace Larium\Search\MongoDb;

use MongoDB\Collection;
use Larium\Search\Result;
use Larium\Search\Criteria;
use Larium\Search\SearchEngine;

class MongoDbSearchEngine implements SearchEngine
{
    private $builders = [];

    public function __construct(
        private readonly FilterBuilder $filterBuilder,
        private readonly Collection $collection,
    ) {
    }

    public function match(Criteria $criteria): Result
    {
        foreach ($this->builders as $builder) {
            if ($builder->supports($criteria)) {
                $builder->build($criteria, $this->filterBuilder);
            }
        }

        return new MongoDbResult($this->filterBuilder, $this->collection);
    }

    public function add(Builder $builder): self
    {
        $this->builders[] = $builder;

        return $this;
    }
}
