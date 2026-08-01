<?php

namespace App\PhyloTree\Contract;

use App\PhyloTree\Contract\TaxonMetadataInterface;
interface TreeNodeInterface
{
    public function getId(): int;

    public function getParent(): ?TreeNodeInterface;

    public function setParent(?TreeNodeInterface $parent): void;

    /**
     * @return TreeNodeInterface[]
     */
    public function getChildren(): array;

    public function addChild(TreeNodeInterface $child): void;

    public function removeChild(TreeNodeInterface $child): void;


    public function getLeft(): int;

    public function setLeft(int $left): void;


    public function getRight(): int;

    public function setRight(int $right): void;


    public function isLeaf(): bool;


    public function getMetadata(): ?TreeNodeMetadataInterface;

    public function setMetadata(
        TreeNodeMetadataInterface $metadata
    ): void;
}
