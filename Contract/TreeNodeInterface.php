<?php

namespace App\SymfonyTreeEngine\Contract;

interface TreeNodeInterface
{
    public function getId(): string;

    public function getLft(): int;

    public function getRgt(): int;

    public function getMetadata(): TaxonMetadataInterface;
}
