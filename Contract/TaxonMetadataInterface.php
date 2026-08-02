<?php

namespace App\PhyloTree\Contract;

use App\PhyloTree\Enum\TaxonomicRankEnum;
interface TaxonMetadataInterface
{
    public function getScientificName(): ?string;

    public function getCommonName(): ?string;

    public function getRank(): ?TaxonomicRankEnum;

    public function isExtinct(): ?bool;
}
