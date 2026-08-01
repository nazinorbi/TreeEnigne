<?php

namespace App\PhyloTree\Contract;



interface NestedSetNodeInterface extends TreeNodeInterface
{
    public function getLeft(): int;

    public function getRight(): int;

    public function getLevel(): int;
}

