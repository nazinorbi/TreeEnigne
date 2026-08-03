<?php

declare(strict_types=1);

namespace App\Phylotree\Contracts;

use App\PhyloTree\Contracts;


interface TreeInterface extends
    TreeQueryInterface,
    TreeMutationInterface,
    TreeValidationInterface,
    TreeStatisticsInterface
{
}
