<?php

declare(strict_types=1);

namespace App\Phylotree\Contract;

use App\PhyloTree\Contract;


interface TreeInterface extends
    TreeQueryInterface,
    TreeMutationInterface,
    TreeValidationInterface,
    TreeStatisticsInterface
{
}
