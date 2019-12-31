<?php

declare(strict_types = 1);

namespace Larium\Search;

use Countable;

interface Result extends Countable
{
    public function fetch(int $offset, int $limit): array;

    public function getCountField(): ?string;

    public function setCountField(string $countField): void;

    public function setCountCallable(callable $function): void;
}
