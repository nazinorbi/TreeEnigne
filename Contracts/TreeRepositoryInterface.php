<?php

namespace App\PhyloTree\Contracts;
interface TreeRepositoryInterface
{
    public function load(object $Root): void;

    /**
     * Finds a node by its identifier.
     */
    public function find(int|string $id): ?TreeNodeInterface;

    /**
     * Returns the root node.
     */
    public function findRoot(): ?TreeNodeInterface;

    /**
     * Checks whether the node exists.
     */
    public function exists(TreeNodeInterface $node): bool;

    /**
     * Returns the direct children of a node.
     *
     * @return iterable<TreeNodeInterface>
     */
    public function getChildren(TreeNodeInterface $node): iterable;

    /**
     * Returns all descendants of a node.
     *
     * @return iterable<TreeNodeInterface>
     */
    public function getDescendants(TreeNodeInterface $node): iterable;

    /**
     * Returns all ancestors of a node.
     *
     * @return iterable<TreeNodeInterface>
     */
    public function getAncestors(TreeNodeInterface $node): iterable;

    /**
     * Returns all leaf nodes in a subtree.
     *
     * @return iterable<TreeNodeInterface>
     */
    public function getLeaves(TreeNodeInterface $node): iterable;

    /**
     * Returns the complete subtree including the node itself.
     *
     * @return iterable<TreeNodeInterface>
     */
    public function getSubtree(TreeNodeInterface $node): iterable;

}
