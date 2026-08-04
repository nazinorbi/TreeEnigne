<?php

namespace App\PhyloTree\Contracts;

interface TreeNodeRepositoryInterface
{
    public function find(int|string $id): ?TreeNodeInterface;

    public function findRoot(): ?TreeNodeInterface;

    public function findParent(TreeNodeInterface $node): ?TreeNodeInterface;

    public function findChildren(TreeNodeInterface $node): iterable;

    public function findDescendants(TreeNodeInterface $node): iterable;

    public function findAncestors(TreeNodeInterface $node): iterable;

    public function findSiblings(TreeNodeInterface $node): iterable;

    public function findNextSibling(TreeNodeInterface $node): ?TreeNodeInterface;

    public function findPreviousSibling(TreeNodeInterface $node): ?TreeNodeInterface;

    public function countChildren(TreeNodeInterface $node): int;

    public function countDescendants(TreeNodeInterface $node): int;
}
