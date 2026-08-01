<?php
declare(strict_types=1);

namespace Nazimecki\Phylotree\Contract;

interface TreeValidationInterface
{
public function validate(): bool;

/**
* @return string[]
*/
public function getValidationErrors(): array;
}
