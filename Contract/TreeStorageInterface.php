<?php

namespace App\PhyloTree\Contract;

interface TreeStorageInterface
{
    public function save(object $Root): void;

    public function delete(object $Root): void;

    public function transaction(object $Root): void;

    public function unlock(object $Root): void;

    public function flush(object $Root): void;
    /**
     * Starts a transaction.
     */
    public function beginTransaction(): void;

    /**
     * Commits the current transaction.
     */
    public function commit(): void;

    /**
     * Rolls back the current transaction.
     */
    public function rollback(): void;

    /**
     * Marks a node for persistence.
     */
    public function persist(TreeNodeInterface $node): void;

    /**
     * Removes a node.
     */
    public function remove(TreeNodeInterface $node): void;

    /**
     * Reloads the node from storage.
     */
    public function refresh(TreeNodeInterface $node): void;

    /**
     * Locks a single node.
     */
    public function lock(TreeNodeInterface $node): void;

    /**
     * Locks an entire subtree.
     */
    public function lockSubtree(TreeNodeInterface $node): void;

    /**
     * Returns the repository implementation.
     */
    public function getRepository(): TreeRepositoryInterface;
}
