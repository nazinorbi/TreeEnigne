<?php

namespace App\PhyloTree\Model\Tree;

use App\PhyloTree\Contract\TreeNodeInterface;
use App\Phylotree\Contract\TreeNodeMetadataInterface;

class TreeNode implements TreeNodeInterface
{
    private int $Id;

    private int $Left;

    private int $Right;

    private int $Level ;

    private ?TreeNodeMetadataInterface $metadata;


    public function __construct(
        int|string|null $id = null,
        int $left = 0,
        int $right = 0,
        int $level = 0,
        ?TreeNodeMetadataInterface $metadata = null
    ) {
        $this->id = $id;
        $this->left = $left;
        $this->right = $right;
        $this->level = $level;
        $this->metadata = $metadata;
    }
    public function getId(): int
    {
        return $this->id;
    }

    public function getLeft(): int
    {
        return $this->left;
    }

    public function getRight(): int
    {
        return $this->right;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function getMetadata(): ?TreeNodeMetadataInterface
    {
        return $this->metadata;
    }

    /**
     * Nested Set alapján eldönti,
     * hogy a csomópont levél-e.
     */
    public function isLeaf(): bool
    {
        return ($this->right - $this->left) === 1;
    }


    /**
     * Gyökér vizsgálata.
     */
    public function isRoot(): bool
    {
        return $this->level === 0;
    }

    /**
     * Fa információk
     */
    public function getRoot(): TreeNodeInterface {

    }

    public function isEmpty(): bool {

    }

    public function count(): int {

    }

    public function getMaxDepth(): int {

    }

    /**
     * Csomópont keresés
     */

    public function getNode(int|string $id): ?TreeNodeInterface {

    }

    public function hasNode(int|string $id): bool {

    }

    public function find(callable $predicate): ?TreeNodeInterface {

    }

    /**
     * @return TreeNodeInterface[]
     */
    public function findAll(callable $predicate): array {

    }

    /**
     * Strukturális lekérdezések
     */

    public function getParent(TreeNodeInterface $node): ?TreeNodeInterface {

    }

    /**
     * @return TreeNodeInterface[]
     */
    public function getChildren(TreeNodeInterface $node): array {

    }

    /**
     * @return TreeNodeInterface[]
     */
    public function getDescendants(TreeNodeInterface $node): array {

    }

    /**
     * @return TreeNodeInterface[]
     */
    public function getAncestors(TreeNodeInterface $node): array {

    }

    /**
     * @return TreeNodeInterface[]
     */
    public function getSiblings(TreeNodeInterface $node): array {

    }

    /**
     * Biólógiai lekérdezések
     */

    /**
     * @return TreeNodeInterface[]
     */
    public function getLeaves(): array {

    }

    /**
     * @return TreeNodeInterface[]
     */
    public function getExtinctTaxa(): array {

    }

    /**
     * @return TreeNodeInterface[]
     */
    public function getLivingTaxa(): array {

    }

    /**
     * @return TreeNodeInterface[]
     */
    public function getByRank(TaxonomicRankEnum $rank): array {

    }

    /**
     * Nested Set számítások
     */

    /**
     * A csomópont szélessége Nested Set érték alapján.
     */
    public function getWidth(): int
    {
        return $this->right - $this->left + 1;
    }

    /**
     * A részfa mérete csomópontokban.
     */
    public function getSubtreeSize(): int
    {
        return intdiv(
            $this->right - $this->left + 1,
            2
        );
    }

    public function isAncestor(
        TreeNodeInterface $ancestor,
        TreeNodeInterface $node
    ): bool {

    }

    public function isDescendant(
        TreeNodeInterface $node,
        TreeNodeInterface $ancestor
    ): bool {

    }

    /**
     * Módósítások
     */
    public function add(TreeNodeInterface $node): void {

    }

    public function remove(TreeNodeInterface $node): void {

    }

    public function move(
        TreeNodeInterface $source,
        TreeNodeInterface $destination
    ): void {

    }

    public function copy(
        TreeNodeInterface $source,
        TreeNodeInterface $destination
    ): TreeNodeInterface {

    }

    /**
     * Validáció
     */
    public function validate(): bool {

    }

    public function isValid(): bool {

    }

    /**
     * @return string[]
     */
    public function getValidationErrors(): array {

    }

    /**
     * Iteráció
     */
    public function getIterator(): Traversable {

    }

}
