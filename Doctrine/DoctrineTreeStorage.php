<?php

declare(strict_types=1);

namespace App\PhyloTree\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Throwable;

use App\PhyloTree\Contracts\TreeNodeInterface;
use App\PhyloTree\Contracts\TreeStorageInterface;
use App\PhyloTree\Contracts\TreeNodeRepositoryInterface;
use App\PhyloTree\Exception\TreeStorageException;

/**
 * Doctrine implementation of the TreeStorageInterface.
 *
 * This class is responsible for all database level operations
 * required by the TreeEngine.
 *
 * Business rules are intentionally NOT implemented here.
 * Those belong to the Tree class.
 *
 * This class performs:
 *
 *  - Transaction handling
 *  - Native SQL execution
 *  - Tree navigation
 *  - Tree persistence
 *  - Nested Set manipulation
 *
 * Version:
 *      TreeEngine V1
 */
final class DoctrineTreeStorage implements TreeStorageInterface
{
    /**
     * Doctrine Entity Manager.
     */
    private EntityManagerInterface $entityManager;

    /**
     * Doctrine DBAL connection.
     */
    private Connection $connection;

    /**
     * Repository used to load TreeNodes.
     */
    private TreeNodeRepositoryInterface $repository;

    /**
     * Constructor.
     *
     * Initializes the storage layer and acquires the
     * Doctrine database connection.
     */
    public function __construct(
        EntityManagerInterface      $entityManager,
        TreeNodeRepositoryInterface $repository
    )
    {
        $this->entityManager = $entityManager;
        $this->connection = $entityManager->getConnection();
        $this->repository = $repository;
    }

    /* ==========================================================
     * Transaction Management
     * ==========================================================
     */

    /**
     * Starts a database transaction.
     *
     * @throws TreeStorageException
     */
    public function beginTransaction(): void
    {
        try {
            $this->connection->beginTransaction();
        } catch (Throwable $exception) {
            throw new TreeStorageException(
                'Unable to start transaction.',
                previous: $exception
            );
        }
    }

    /**
     * Commits the current database transaction.
     *
     * @throws TreeStorageException
     */
    public function commit(): void
    {
        try {
            $this->connection->commit();
        } catch (Throwable $exception) {
            throw new TreeStorageException(
                'Unable to commit transaction.',
                previous: $exception
            );
        }
    }

    /**
     * Rolls back the current database transaction.
     *
     * @throws TreeStorageException
     */
    public function rollback(): void
    {
        try {
            if ($this->connection->isTransactionActive()) {
                $this->connection->rollBack();
            }
        } catch (Throwable $exception) {
            throw new TreeStorageException(
                'Unable to rollback transaction.',
                previous: $exception
            );
        }
    }

    /* ==========================================================
     * Locking
     * ==========================================================
     */

    public function lock(): void
    {
        // TODO: Implement lock() method.
    }

    /**
     * Locks the entire tree.
     *
     * This method will later contain
     * database specific locking logic.
     *
     * @throws TreeStorageException
     */
    public function lockTree(): void
    {
        throw new LogicException(
            'lockTree() is not implemented yet.'
        );
    }

    /**
     * Releases every active tree lock.
     *
     * @throws TreeStorageException
     */
    public function unlockTree(): void
    {
        throw new LogicException(
            'unlockTree() is not implemented yet.'
        );
    }

    /**
     * Locks a subtree before structural modification.
     *
     * @throws TreeStorageException
     */
    public function lockSubtree(
        TreeNodeInterface $node
    ): void
    {
        throw new LogicException(
            'lockSubtree() is not implemented yet.'
        );
    }

    /* ==========================================================
 * Node Lookup
 * ==========================================================
 */

    /**
     * Checks whether a node exists.
     *
     * @throws TreeStorageException
     */
    public function exists(int|string $id): bool
    {
        try {
            return $this->repository->find($id) !== null;
        } catch (Throwable $exception) {
            throw new TreeStorageException(
                sprintf('Unable to determine whether node "%s" exists.', $id),
                previous: $exception
            );
        }
    }

    /**
     * Returns a node by its identifier.
     *
     * @throws TreeStorageException
     */
    public function getNode(int|string $id): ?TreeNodeInterface
    {
        try {
            return $this->repository->find($id);
        } catch (Throwable $exception) {
            throw new TreeStorageException(
                sprintf('Unable to load node "%s".', $id),
                previous: $exception
            );
        }
    }

    /**
     * Refreshes a node from the database.
     *
     * @throws TreeStorageException
     */
    public function refresh(
        TreeNodeInterface $node
    ): TreeNodeInterface
    {
        try {

            $this->entityManager->refresh($node);

            return $node;

        } catch (Throwable $exception) {

            throw new TreeStorageException(
                'Unable to refresh TreeNode.',
                previous: $exception
            );

        }
    }

    /* ==========================================================
     * Navigation
     * ==========================================================
     */

    /**
     * Returns the root node.
     *
     * @throws TreeStorageException
     */
    public function getRoot(): ?TreeNodeInterface
    {
        try {

            return $this->repository->findRoot();

        } catch (Throwable $exception) {

            throw new TreeStorageException(
                'Unable to load root node.',
                previous: $exception
            );

        }
    }

    /**
     * Returns the parent node.
     *
     * @throws TreeStorageException
     */
    public function getParent(
        TreeNodeInterface $node
    ): ?TreeNodeInterface
    {
        try {

            return $this->repository->findParent($node);

        } catch (Throwable $exception) {

            throw new TreeStorageException(
                'Unable to load parent node.',
                previous: $exception
            );

        }
    }

    /**
     * Returns the children of a node.
     *
     * If directOnly is FALSE then every descendant
     * will be returned.
     *
     * @throws TreeStorageException
     */
    public function getChildren(
        TreeNodeInterface $node,
        bool              $directOnly = true
    ): iterable
    {
        try {

            if ($directOnly) {
                return $this->repository->findChildren($node);
            }

            return $this->repository->findDescendants($node);

        } catch (Throwable $exception) {

            throw new TreeStorageException(
                'Unable to load child nodes.',
                previous: $exception
            );

        }
    }

    /**
     * Returns every descendant.
     *
     * @throws TreeStorageException
     */
    public function getDescendants(
        TreeNodeInterface $node
    ): iterable
    {
        try {

            return $this->repository->findDescendants($node);

        } catch (Throwable $exception) {

            throw new TreeStorageException(
                'Unable to load descendants.',
                previous: $exception
            );

        }
    }

    /**
     * Returns every ancestor.
     *
     * @throws TreeStorageException
     */
    public function getAncestors(
        TreeNodeInterface $node
    ): iterable
    {
        try {

            return $this->repository->findAncestors($node);

        } catch (Throwable $exception) {

            throw new TreeStorageException(
                'Unable to load ancestors.',
                previous: $exception
            );

        }
    }

    /**
     * Returns sibling nodes.
     *
     * @throws TreeStorageException
     */
    public function getSiblings(
        TreeNodeInterface $node
    ): iterable
    {
        try {

            return $this->repository->findSiblings($node);

        } catch (Throwable $exception) {

            throw new TreeStorageException(
                'Unable to load siblings.',
                previous: $exception
            );

        }
    }

    /**
     * Returns the next sibling.
     *
     * @throws TreeStorageException
     */
    public function getNextSibling(
        TreeNodeInterface $node
    ): ?TreeNodeInterface
    {
        try {

            return $this->repository->findNextSibling($node);

        } catch (Throwable $exception) {

            throw new TreeStorageException(
                'Unable to load next sibling.',
                previous: $exception
            );

        }
    }

    /**
     * Returns the previous sibling.
     *
     * @throws TreeStorageException
     */
    public function getPreviousSibling(
        TreeNodeInterface $node
    ): ?TreeNodeInterface
    {
        try {

            return $this->repository->findPreviousSibling($node);

        } catch (Throwable $exception) {

            throw new TreeStorageException(
                'Unable to load previous sibling.',
                previous: $exception
            );

        }
    }

    /* ==========================================================
         * Statistics
         * ==========================================================
         */

    /**
     * Returns the number of direct children.
     *
     * @throws TreeStorageException
     */
    public function countChildren(
        TreeNodeInterface $node
    ): int {
        try {

            return $this->repository->countChildren($node);

        } catch (Throwable $exception) {

            throw new TreeStorageException(
                'Unable to count child nodes.',
                previous: $exception
            );

        }
    }

    /**
     * Returns the number of descendants.
     *
     * @throws TreeStorageException
     */
    public function countDescendants(
        TreeNodeInterface $node
    ): int {
        try {

            return $this->repository->countDescendants($node);

        } catch (Throwable $exception) {

            throw new TreeStorageException(
                'Unable to count descendants.',
                previous: $exception
            );

        }
    }

    /**
     * Returns the node depth.
     *
     * @throws TreeStorageException
     */
    public function getDepth(
        TreeNodeInterface $node
    ): int {
        return $node->getLevel();
    }

    /**
     * Returns the subtree height.
     *
     * Height calculation will be implemented
     * using a dedicated SQL query.
     *
     * @throws TreeStorageException
     */
    public function getHeight(
        TreeNodeInterface $node
    ): int {
        throw new LogicException(
            'getHeight() is not implemented yet.'
        );
    }

    /* ==========================================================
     * Tree Modification
     * ==========================================================
     */

    /**
     * Appends a child node.
     *
     * Nested Set Algorithm:
     *
     * 1. Lock subtree
     * 2. Create gap
     * 3. Update node values
     * 4. Persist node
     * 5. Commit transaction
     *
     * @throws TreeStorageException
     */
    public function appendChild(
        TreeNodeInterface $parent,
        TreeNodeInterface $child
    ): void {

        throw new LogicException(
            'appendChild() is not implemented yet.'
        );

    }

    /**
     * Prepends a child node.
     *
     * @throws TreeStorageException
     */
    public function prependChild(
        TreeNodeInterface $parent,
        TreeNodeInterface $child
    ): void {

        throw new LogicException(
            'prependChild() is not implemented yet.'
        );

    }

    /**
     * Inserts a node before another node.
     *
     * @throws TreeStorageException
     */
    public function insertBefore(
        TreeNodeInterface $reference,
        TreeNodeInterface $newNode
    ): void {

        throw new LogicException(
            'insertBefore() is not implemented yet.'
        );

    }

    /**
     * Inserts a node after another node.
     *
     * @throws TreeStorageException
     */
    public function insertAfter(
        TreeNodeInterface $reference,
        TreeNodeInterface $newNode
    ): void {

        throw new LogicException(
            'insertAfter() is not implemented yet.'
        );

    }

    /**
     * Moves a subtree.
     *
     * @throws TreeStorageException
     */
    public function moveSubtree(
        TreeNodeInterface $node,
        TreeNodeInterface $newParent
    ): void {

        throw new LogicException(
            'moveSubtree() is not implemented yet.'
        );

    }

    /**
     * Copies a subtree.
     *
     * @throws TreeStorageException
     */
    public function copySubtree(
        TreeNodeInterface $source,
        TreeNodeInterface $destination
    ): TreeNodeInterface {

        throw new LogicException(
            'copySubtree() is not implemented yet.'
        );

    }
    /**
     * Removes a subtree.
     *
     * @throws TreeStorageException
     */
    public function removeSubtree(
        TreeNodeInterface $node
    ): void {

        throw new LogicException(
            'removeSubtree() is not implemented yet.'
        );

    }

    /**
     * Removes a single node.
     *
     * If $promoteChildren is TRUE then the direct
     * children of the node will be attached to
     * the parent node.
     *
     * @throws TreeStorageException
     */
    public function removeNode(
        TreeNodeInterface $node,
        bool $promoteChildren = false
    ): void {

        throw new LogicException(
            'removeNode() is not implemented yet.'
        );

    }

    /**
     * Replaces one node with another.
     *
     * @throws TreeStorageException
     */
    public function replaceNode(
        TreeNodeInterface $old,
        TreeNodeInterface $new
    ): void {

        throw new LogicException(
            'replaceNode() is not implemented yet.'
        );

    }

    /**
     * Swaps the position of two nodes.
     *
     * @throws TreeStorageException
     */
    public function swapNodes(
        TreeNodeInterface $first,
        TreeNodeInterface $second
    ): void {

        throw new LogicException(
            'swapNodes() is not implemented yet.'
        );

    }

    /* ==========================================================
     * Maintenance
     * ==========================================================
     */

    /**
     * Verifies the structural integrity
     * of the Nested Set tree.
     *
     * @throws TreeStorageException
     */
    public function verify(): bool
    {

        throw new LogicException(
            'verify() is not implemented yet.'
        );

    }

    /**
     * Repairs an inconsistent tree.
     *
     * @throws TreeStorageException
     */
    public function repair(): void
    {

        throw new LogicException(
            'repair() is not implemented yet.'
        );

    }

    /**
     * Rebuilds the complete Nested Set tree.
     *
     * @throws TreeStorageException
     */
    public function rebuild(): void
    {

        throw new LogicException(
            'rebuild() is not implemented yet.'
        );

    }

    /**
     * Optimizes the underlying storage.
     *
     * @throws TreeStorageException
     */
    public function optimize(): void
    {

        throw new LogicException(
            'optimize() is not implemented yet.'
        );

    }

    /* ==========================================================
     * Persistence
     * ==========================================================
     */

    /**
     * Marks a TreeNode for persistence.
     *
     * @throws TreeStorageException
     */
    public function persist(
        TreeNodeInterface $node
    ): void
    {
        try {

            $this->entityManager->persist($node);

        } catch (Throwable $exception) {

            throw new TreeStorageException(
                'Unable to persist TreeNode.',
                previous: $exception
            );

        }
    }

    /**
     * Marks a TreeNode for removal.
     *
     * @throws TreeStorageException
     */
    public function remove(
        TreeNodeInterface $node
    ): void
    {
        try {

            $this->entityManager->remove($node);

        } catch (Throwable $exception) {

            throw new TreeStorageException(
                'Unable to remove TreeNode.',
                previous: $exception
            );

        }
    }

    /**
     * Flushes all pending Doctrine changes.
     *
     * @throws TreeStorageException
     */
    public function flush(): void
    {
        try {

            $this->entityManager->flush();

        } catch (Throwable $exception) {

            throw new TreeStorageException(
                'Unable to flush EntityManager.',
                previous: $exception
            );

        }
    }

    /**
     * Clears the Doctrine UnitOfWork.
     *
     * @throws TreeStorageException
     */
    public function clear(): void
    {
        try {

            $this->entityManager->clear();

        } catch (Throwable $exception) {

            throw new TreeStorageException(
                'Unable to clear EntityManager.',
                previous: $exception
            );

        }
    }

    /* ==========================================================
     * Nested Set SQL Primitives
     * ==========================================================
     */

    /**
     * Creates an empty gap inside the nested set.
     *
     * Every left and right value greater than or equal
     * to the given position will be shifted by $width.
     */
    private function createGap(
        int $from,
        int $width
    ): void {

        throw new LogicException(
            'createGap() is not implemented yet.'
        );

    }

    /**
     * Closes an existing gap.
     *
     * This operation is the inverse of createGap().
     */
    private function closeGap(
        int $from,
        int $width
    ): void {

        throw new LogicException(
            'closeGap() is not implemented yet.'
        );

    }

    /**
     * Moves a complete nested set interval.
     *
     * Only left and right values are modified.
     */
    private function moveInterval(
        int $left,
        int $right,
        int $offset
    ): void {

        throw new LogicException(
            'moveInterval() is not implemented yet.'
        );

    }

    /**
     * Changes the level of an entire subtree.
     */
    private function shiftLevel(
        int $left,
        int $right,
        int $delta
    ): void {

        throw new LogicException(
            'shiftLevel() is not implemented yet.'
        );

    }

    /**
     * Updates the parent identifier.
     */
    private function updateParent(
        TreeNodeInterface $node,
        ?TreeNodeInterface $parent
    ): void {

        throw new LogicException(
            'updateParent() is not implemented yet.'
        );

    }

    /**
     * Locks the specified interval.
     *
     * Database specific implementation.
     */
    private function lockInterval(
        int $left,
        int $right
    ): void {

        throw new LogicException(
            'lockInterval() is not implemented yet.'
        );

    }

    /**
     * Calculates the width of a subtree.
     *
     * Formula:
     *
     * width = right - left + 1
     */
    private function calculateWidth(
        TreeNodeInterface $node
    ): int {

        return
            $node->getRight() -
            $node->getLeft() +
            1;

    }

    /**
     * Normalizes the complete tree.
     *
     * Used internally by rebuild() and repair().
     */
    private function normalize(): void
    {

        throw new LogicException(
            'normalize() is not implemented yet.'
        );

    }
}
