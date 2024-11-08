<?php

namespace Larium\Search\MongoDb;

use Larium\Search\Criteria;

class FruitColorBuilder implements Builder
{
    public function supports(Criteria $criteria): bool
    {
        return isset($criteria->filtering->fields['color']);
    }

    public function build(Criteria $criteria, FilterBuilder $filterBuilder): void
    {
        $filterBuilder->append([
                '$or' => [
                    ['qty' => ['$gt' => 5]],
                    ['color' => $criteria->filtering->fields['color']]
                ]
        ]);
    }
}
