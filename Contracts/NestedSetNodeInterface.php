<?php

namespace App\PhyloTree\Contracts;



interface NestedSetNodeInterface extends TreeNodeInterface
{
    public function getLeft(): int;

    public function getRight(): int;

    public function getLevel(): int;
}

