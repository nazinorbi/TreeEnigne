<?php

declare(strict_types=1);

namespace App\PhyloTree\Contracts;

use App\PhyloTree\Contracts\TreeNodeInterface;
use  App\PhyloTree\Exception\TreeStorageException;


interface TreeStorageInterface
{
    /*
    |--------------------------------------------------------------------------
    | Transaction Management
    |--------------------------------------------------------------------------
    */

    public function beginTransaction(): void;

    public function commit(): void;

    public function rollback(): void;


    /*
    |--------------------------------------------------------------------------
    | Locking
    |--------------------------------------------------------------------------
    */

    public function lock(): void;
    public function lockTree(): void;

    public function unlockTree(): void;

    public function lockSubtree(TreeNodeInterface $node): void;


    /*
    |--------------------------------------------------------------------------
    | Node Lookup
    |--------------------------------------------------------------------------
    */

    public function exists(int|string $id): bool;

    public function getNode(int|string $id): ?TreeNodeInterface;

    public function refresh(TreeNodeInterface $node): TreeNodeInterface;


    /*
    |--------------------------------------------------------------------------
    | Navigation
    |--------------------------------------------------------------------------
    */

    public function getRoot(): ?TreeNodeInterface;

    public function getParent(TreeNodeInterface $node): ?TreeNodeInterface;

    public function getChildren(
        TreeNodeInterface $node,
        bool $directOnly = true
    ): iterable;

    public function getDescendants(
        TreeNodeInterface $node
    ): iterable;

    public function getAncestors(
        TreeNodeInterface $node
    ): iterable;

    public function getSiblings(
        TreeNodeInterface $node
    ): iterable;

    public function getNextSibling(
        TreeNodeInterface $node
    ): ?TreeNodeInterface;

    public function getPreviousSibling(
        TreeNodeInterface $node
    ): ?TreeNodeInterface;


    /*
    |--------------------------------------------------------------------------
    | Statistics
    |--------------------------------------------------------------------------
    */

    public function countChildren(
        TreeNodeInterface $node
    ): int;

    public function countDescendants(
        TreeNodeInterface $node
    ): int;

    public function getDepth(
        TreeNodeInterface $node
    ): int;

    public function getHeight(
        TreeNodeInterface $node
    ): int;


    /*
    |--------------------------------------------------------------------------
    | Tree Modification
    |--------------------------------------------------------------------------
    */

    public function appendChild(
        TreeNodeInterface $parent,
        TreeNodeInterface $child
    ): void;

    public function prependChild(
        TreeNodeInterface $parent,
        TreeNodeInterface $child
    ): void;

    public function insertBefore(
        TreeNodeInterface $reference,
        TreeNodeInterface $newNode
    ): void;

    public function insertAfter(
        TreeNodeInterface $reference,
        TreeNodeInterface $newNode
    ): void;

    public function moveSubtree(
        TreeNodeInterface $node,
        TreeNodeInterface $newParent
    ): void;

    public function copySubtree(
        TreeNodeInterface $source,
        TreeNodeInterface $destination
    ): TreeNodeInterface;

    public function removeSubtree(
        TreeNodeInterface $node
    ): void;

    public function removeNode(
        TreeNodeInterface $node,
        bool $promoteChildren = false
    ): void;

    public function replaceNode(
        TreeNodeInterface $old,
        TreeNodeInterface $new
    ): void;

    public function swapNodes(
        TreeNodeInterface $first,
        TreeNodeInterface $second
    ): void;


    /*
    |--------------------------------------------------------------------------
    | Maintenance
    |--------------------------------------------------------------------------
    */

    public function verify(): bool;

    public function repair(): void;

    public function rebuild(): void;

    public function optimize(): void;


    /*
    |--------------------------------------------------------------------------
    | Persistence
    |--------------------------------------------------------------------------
    */

    public function persist(TreeNodeInterface $node): void;

    public function remove(TreeNodeInterface $node): void;

    public function flush(): void;

    public function clear(): void;
}
