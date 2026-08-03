<?php
declare(strict_types=1);

namespace App\Phylotree\Contracts;

interface TreeValidationInterface
{
public function validate(): bool;

/**
* @return string[]
*/
public function getValidationErrors(): array;
}
