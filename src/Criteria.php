<?php

declare(strict_types = 1);

namespace Larium\Search;

class Criteria
{
    public $resourceName;

    public $filtering;

    public $ordering;

    public $paginating;

    private function __construct(
        string $resourceName,
        Criteria\Filtering $filtering,
        Criteria\Ordering $ordering,
        Criteria\Paginating $paginating
    ) {
        $this->resourceName = $resourceName;
        $this->filtering = $filtering;
        $this->ordering = $ordering;
        $this->paginating = $paginating;
    }

    /**
     * Create an instance of Criteria from query params.
     *
     * @param string $resourceName
     * @param array $queryParams
     * @return Criteria
     */
    public static function fromQueryParams(
        string $resourceName,
        array $queryParams
    ): Criteria {
        return new self(
            $resourceName,
            Criteria\Filtering::fromQueryParams($queryParams),
            Criteria\Ordering::fromQueryParams($queryParams),
            Criteria\Paginating::fromQueryParams($queryParams)
        );
    }
}
