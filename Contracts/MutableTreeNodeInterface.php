<?php

namespace App\PhyloTree\Contracts;

use App\PhyloTree\Contracts\TreeNodeMetadataInterface;
interface MutableTreeNodeInterface
{
    public function setLeft(int $left): void;

    public function setRight(int $right): void;

    public function setLevel(int $level): void;

    public function setMetadata(TreeNodeMetadataInterface $metadata): void;
}
