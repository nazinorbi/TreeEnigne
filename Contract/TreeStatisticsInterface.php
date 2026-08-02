<?php

declare(strict_types=1);

namespace App\Phylotree\Contract;

interface TreeStatisticsInterface
{
    public function countNodes(): int;

    public function countLeaves(): int;

    public function getDepth(): int;

    public function getHeight(TreeNodeInterface $node): int;

    public function getSubtreeSize(TreeNodeInterface $node): int;
}
