<?php

declare(strict_types=1);

namespace Larium\Search\MongoDb;

use Larium\Search\Criteria;

class FruitNameBuilder implements Builder
{
    public function supports(Criteria $criteria): bool
    {
        return isset($criteria->filtering->fields['name']);
    }

    public function build(Criteria $criteria, FilterBuilder $filterBuilder): void
    {
        $filterBuilder->append([
            'name' => ['$regex' => ".*" . $criteria->filtering->fields['name'] . ".*"]
        ]);
    }
}
