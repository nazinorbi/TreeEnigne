<?php

declare(strict_types=1);

namespace App\PhyloTree\Contract;

interface LeafMetadataInterface extends TaxonMetadataInterface
{
    /**
     * A faj szerzője.
     */
    public function getAuthor(): ?string;
}
