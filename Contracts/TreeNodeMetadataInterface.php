<?php

declare(strict_types=1);

namespace App\PhyloTree\Contracts;

use App\PhyloTree\Enum\TaxonomicRankEnum;

interface TreeNodeMetadataInterface
{
    public function getScientificName(): ?string;

    public function getRank(): ?TaxonomicRankEnum;

    public function isExtinct(): ?bool;
}
