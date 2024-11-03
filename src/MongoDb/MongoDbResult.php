<?php

declare(strict_types=1);

namespace Larium\Search\MongoDb;

use MongoDB\Collection;
use Larium\Search\Result;

class MongoDbResult implements Result
{
    private array $filter = [];

    public function __construct(
        private readonly FilterBuilder $filterBuilder,
        private readonly Collection $collection,
    ) {

    }

    public function fetch(int $offset, int $limit): array
    {
        $filter = $this->normalizeFilter();
        $iterator = $this->collection->find($filter, ['skip' => $offset, 'limit' => $limit]);

        return iterator_to_array($iterator);
    }

    public function getCountField(): ?string
    {
        return null;
    }

    public function setCountField(string $countField): void
    {
    }

    public function setCountCallable(callable $function): void
    {
    }

    public function count(): int
    {
        return $this->collection->countDocuments($this->normalizeFilter());
    }

    private function normalizeFilter(): array
    {
        if (!empty($this->filter)) {
            return $this->filter;
        }

        $this->filter = array_reduce($this->filterBuilder->getArrayCopy(), function (array $result, array $item) {
            $result = array_merge($result, $item);
            return $result;
        }, []);

        return $this->filter;
    }
}
