<?php

declare(strict_types = 1);

namespace Larium\Search\Criteria;

class Ordering
{
    const ASC = 'ASC';

    const DESC = 'DESC';

    const DEFAULT_FIELD = 'name';

    const DEFAULT_DIRECTION = self::ASC;

    public $field;

    public $direction;

    public function __construct(string $field, string $direction)
    {
        $this->field = $field;
        $this->direction = $direction;
    }

    public static function fromQueryParams(array $queryParams): Ordering
    {
        $field = isset($queryParams['sort'])
            ? $queryParams['sort']
            : self::DEFAULT_FIELD;
        $direction = self::DEFAULT_DIRECTION;
        if ('-' === $field[0]) {
            $direction = 'DESC';
            $field = trim($field, '-');
        }

        return new self($field, $direction);
    }
}
