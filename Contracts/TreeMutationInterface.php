<?php

declare(strict_types=1);

namespace App\Phylotree\Contracts;

use App\PhyloTree\Contracts\TreeNodeInterface;
interface TreeMutationInterface
{
    public function insertChild(
        TreeNodeInterface $parent,
        TreeNodeInterface $node
    ): void;

    public function insertBefore(
        TreeNodeInterface $target,
        TreeNodeInterface $node
    ): void;

    public function insertAfter(
        TreeNodeInterface $target,
        TreeNodeInterface $node
    ): void;
}
