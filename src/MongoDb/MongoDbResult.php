<?php

declare(strict_types=1);

namespace Larium\Search\MongoDb;

use MongoDB\Collection;
use Larium\Search\Result;

class MongoDbResult implements Result
{
    private array $query = [];

    public function __construct(
        private readonly FilterBuilder $filterBuilder,
        private readonly Collection $collection,
    ) {

    }

    public function fetch(int $offset, int $limit): array
    {
        $query = $this->getQuery();
        $options = ['skip' => $offset, 'limit' => $limit];
        $options = $this->addSorting($query, $options);
        $iterator = $this->collection->find($query, $options);

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
        $query = $this->getQuery();
        $options = $this->addSorting($query, []);

        return $this->collection->countDocuments($query, $options);
    }

    private function addSorting(array &$filter, array $options): array
    {
        if (isset($filter['sort'])) {
            $options['sort'] = $filter['sort'];
            unset($filter['sort']);
        }

        return $options;
    }

    private function getQuery(): array
    {
        if (!empty($this->query)) {
            return $this->query;
        }

        return $this->query = $this->filterBuilder->getQuery();
    }
}
