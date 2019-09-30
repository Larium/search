<?php

declare(strict_types = 1);

namespace Larium\Search\Criteria;

class Filtering
{
    public $fields;

    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    public static function fromQueryParams(array $queryParams): Filtering
    {
        $fields = $queryParams;
        unset($fields['sort']);

        return new self($fields);
    }
}
