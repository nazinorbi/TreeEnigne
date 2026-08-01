<?php
declare(strict_types=1);

namespace App\Phylotree\Contract;

interface TreeValidationInterface
{
public function validate(): bool;

/**
* @return string[]
*/
public function getValidationErrors(): array;
}
