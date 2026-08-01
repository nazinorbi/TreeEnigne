<?php

namespace App\SymfonyTreeEngine\Contract;

use \App\SymfonyTreeEngine\Contract\TaxonMetadataInterface;
interface TreeNodeInterface
{
    public function getId(): int;

    public function getMetadata(): TaxonMetadataInterface;
}
