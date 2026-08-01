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
     * A részfa mérete csomópontokban.
     */
    public function getSubtreeSize(): int
    {
        return intdiv(
            $this->right - $this->left + 1,
            2
        );
    }

    /**
     * A csomópont szélessége Nested Set érték alapján.
     */
    public function getWidth(): int
    {
        return $this->right - $this->left + 1;
    }

    /**
     * Gyökér vizsgálata.
     */
    public function isRoot(): bool
    {
        return $this->level === 0;
    }

}
