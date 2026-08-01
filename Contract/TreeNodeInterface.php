<?php

namespace App\PhyloTree\Contract;

use App\PhyloTree\Contract\TaxonMetadataInterface;
interface TreeNodeInterface
{
    public function getId(): int;

    public function getMetadata(): TaxonMetadataInterface;
}
