<?php

namespace App\PhyloTree\Contract;

use App\PhyloTree\Contract\TaxonMetadataInterface;
interface TreeNodeInterface
{
    public function getId(): int;

    public function getLeft(): int;

    public function getRight(): int;

    public function getLevel(): int;

    public function getMetadata(): ?TreeNodeMetadataInterface;
}
