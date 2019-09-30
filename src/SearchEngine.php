<?php

declare(strict_types = 1);

namespace Larium\Search;

interface SearchEngine
{
    public function match(Criteria $criteria): Result;
}
