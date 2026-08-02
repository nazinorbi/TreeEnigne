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

    public function getLevel(): int;

    public function setLevel(int $level): void;

    /**
     * Returns left boundary value.
     */
    public function getLft(): ?int;

    /**
     * Sets left boundary value.
     */
    public function setLft(int $lft): static;

    /**
     * Returns right boundary value.
     */
    public function getRgt(): ?int;

    /**
     * Sets right boundary value.
     */
    public function setRgt(int $rgt): static;
}
