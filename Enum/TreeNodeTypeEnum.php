<?php
declare(strict_types = 1);

namespace App\SymfonyTreeEngine\Enum;

namespace App\PhyloTree\Enum;

enum TreeNodeTypeEnum: string
{
    /**
     * Hipotetikus közös ős.
     */
    case NODE = 'node';

    /**
     * Taxon (species, genus stb.).
     */
    case LEAF = 'leaf';
}

