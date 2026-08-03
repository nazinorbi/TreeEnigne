<?php

declare(strict_types=1);

namespace App\PhyloTree\Contracts;

interface NodeMetadataInterface extends TaxonMetadataInterface
{
    /**
     * Bootstrap vagy egyéb támogatottsági érték.
     */
    public function getSupport(): ?float;

    /**
     * Hipotetikus kor (millió év).
     */
    public function getEstimatedAge(): ?float;
}
