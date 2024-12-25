<?php

declare(strict_types=1);

namespace Larium\Search\MongoDb;

use Larium\Search\Criteria;

class CompositeBuilder implements Builder
{
    private array $builders = [];
    public function __construct(...$builders)
    {
        $this->builders = $builders;
    }

    public function supports(Criteria $criteria): bool
    {
        foreach ($this->builders as $builder) {
            if ($builder->supports($criteria)) {
                return true;
            }
        }

        return false;
    }

    public function build(Criteria $criteria, FilterBuilder $filterBuilder): void
    {
        foreach ($this->builders as $builder) {
            $builder->build($criteria, $filterBuilder);
        }
    }
}
