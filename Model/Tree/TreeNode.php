<?php

namespace App\PhyloTree\Model;

use App\PhyloTree\Contract\TreeNodeInterface;
use App\PhyloTree\Contract\TreeNodeMetadataInterface;
use App\PhyloTree\Enum\TaxonomicRankEnum;
use ArrayIterator;
use \IteratorAggregate;

class TreeNode implements TreeNodeInterface, IteratorAggregate
{
    private int $id;
    private int $left = 0;
    private int $right = 0;
    private int $level = 0;

    private ?TreeNodeMetadataInterface $metadata = null;

    private ?TreeNodeInterface $parent = null;

    /**
     * @var TreeNodeInterface[]
     */
    private array $children = [];

    public function __construct( int $id) {

        $this->id = $id;

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

    public function setLevel(int $level): void
    {
        $this->level = $level;
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
        return count($this->children) === 0;
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
    public function getRoot( TreeNodeInterface $node): TreeNodeInterface {

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
     * @return TreeNodeInterface[]
     * Egy node összes leszármazottjának lekérése.
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
    public function getLeftSiblings(TreeNodeInterface $node): array {

    }
    public function getRightSiblings(TreeNodeInterface $node): array {
        return ($this->right - $this->left) === 1;
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
     *
     * még nincs use
     */
    /**public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->children);
    }
    */
    public function getParent(): ?TreeNodeInterface
    {
        return $this->parent;
    }

    public function setParent(?TreeNodeInterface $parent): void
    {
        $this->parent = $parent;
    }

    public function getChildren(): array
    {
        return $this->children;
    }

    public function addChild(TreeNodeInterface $child): void
    {
        $this->children[] = $child;

        $child->setParent($this);
    }

    public function removeChild(TreeNodeInterface $child): void
    {
        foreach ($this->children as $key => $item) {

            if ($item === $child) {

                unset($this->children[$key]);

                $child->setParent(null);

                break;
            }
        }
        $this->children = array_values($this->children);
    }

    public function setLeft(int $left): void
    {
        $this->left = $left;
    }

    public function setRight(int $right): void
    {
        $this->right = $right;
    }

    public function setMetadata(
        TreeNodeMetadataInterface $metadata
    ): void
    {
        $this->metadata = $metadata;
    }

    /**
     * Gyermek node-ok bejárása
     *
     * Átírni Sql be
     */
    public function getIterator(): \Traversable
    {
        return new ArrayIterator($this->children);
    }

    public  function isDescendantOf(?TreeNodeInterface $parent)
    {

    }
    public  function isAncestorOf()
    {

    }
    public  function  getPath()
    {

    }
    public  function  countDescendants()
    {

    }

    public  function  validateTree()
    {

    }

}
