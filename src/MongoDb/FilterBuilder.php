<?php

declare(strict_types=1);

namespace Larium\Search\MongoDb;

use ArrayObject;

class FilterBuilder extends ArrayObject
{
    public function getQuery(): array
    {
        return array_reduce(
            $this->getArrayCopy(),
            fn (array $result, array $item) => array_merge($result, $item),
            []
        );
    }
}
