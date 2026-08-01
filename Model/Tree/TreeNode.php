<?php

namespace App\PhyloTree\Model\Tree;

use App\PhyloTree\Contract\TreeNodeMetadataInterface;

class TreeNode implements TreeNodeInterface
{
    private int $id;

    private int $left;

    private int $right;

    private int $level;

    private TreeNodeMetadataInterface $metadata;


    public function isLeaf(): bool
    {
        return $this->right === $this->left + 1;
    }
}
