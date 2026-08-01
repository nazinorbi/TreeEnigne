<?php

declare(strict_types=1);

namespace App\PhyloTree\Contract;

use App\PhyloTree\Enum\TreeNodeTypeEnum;

interface TreeNodeMetadataInterface
{
    public function getType(): TreeNodeTypeEnum;

    public function getMetadata(): TaxonMetadataInterface;
}
