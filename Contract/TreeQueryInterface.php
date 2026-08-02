<?php
declare(strict_types=1);

namespace App\PhyloTree\Contract;

interface TreeQueryInterface
{
    public function getRoot(): TreeNodeInterface;

    public function getNode(int|string $id): ?TreeNodeInterface;

    public function getParent(TreeNodeInterface $node): ?TreeNodeInterface;

    /**
     * @return TreeNodeInterface[]
     */
    public function getChildren(TreeNodeInterface $node): array;

    /**
     * @return TreeNodeInterface[]
     */
    public function getDescendants(TreeNodeInterface $node): array;

    /**
     * @return TreeNodeInterface[]
     */
    public function getAncestors(TreeNodeInterface $node): array;

    /**
     * @return TreeNodeInterface[]
     */
    public function getLeaves(?TreeNodeInterface $root = null): array;

    /**
     * @return TreeNodeInterface[]
     */
    public function getSiblings(TreeNodeInterface $node): array;

    public function isLeaf(TreeNodeInterface $node): bool;

    public function isRoot(TreeNodeInterface $node): bool;

    public function contains(TreeNodeInterface $node): bool;
}
