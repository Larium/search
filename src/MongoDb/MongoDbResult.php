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
        $options = ['skip' => $offset, 'limit' => $limit];
        $options = $this->addSorting($filter, $options);
        $iterator = $this->collection->find($filter, $options);

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
        $filter = $this->normalizeFilter();
        $options = $this->addSorting($filter, []);

        return $this->collection->countDocuments($filter, $options);
    }

    private function addSorting(array &$filter, array $options): array
    {
        if (isset($filter['sort'])) {
            $options['sort'] = $filter['sort'];
            unset($filter['sort']);
        }

        return $options;
    }

    private function normalizeFilter(): array
    {
        if (!empty($this->filter)) {
            return $this->filter;
        }

        $this->filter = array_reduce(
            $this->filterBuilder->getArrayCopy(),
            fn (array $result, array $item) => array_merge($result, $item),
            []
        );

        return $this->filter;
    }
}
