<?php

declare(strict_types = 1);

namespace Larium\Search\Pagerfanta;

use Larium\Search\Result;
use Pagerfanta\Adapter\AdapterInterface;

class SearchAdapter implements AdapterInterface
{
    private $result;

    public function __construct(Result $result)
    {
        $this->result = $result;
    }

    /**
     * {@inheritDoc}
     */
    public function getNbResults(): int
    {
        return $this->result->count();
    }

    /**
     * {@inheritDoc}
     */
    public function getSlice($offset, $length): iterable
    {
        return $this->result->fetch($offset, $length);
    }
}
