<?php

declare(strict_types=1);

namespace App\PhyloTree\Enum;

enum TaxonomicRankEnum: string
{
    case DOMAIN = 'domain';

    case KINGDOM = 'kingdom';
    case SUBKINGDOM = 'subkingdom';
    case INFRAKINGDOM = 'infrakingdom';

    case PHYLUM = 'phylum';
    case SUBPHYLUM = 'subphylum';
    case INFRAPHYLUM = 'infraphylum';

    case CLASS = 'class';
    case SUBCLASS = 'subclass';
    case INFRACLASS = 'infraclass';

    case ORDER = 'order';
    case SUBORDER = 'suborder';
    case INFRAORDER = 'infraorder';

    case FAMILY = 'family';
    case SUBFAMILY = 'subfamily';
    case INFRAFAMILY = 'infrafamily';

    case GENUS = 'genus';
    case SUBGENUS = 'subgenus';

    case SPECIES = 'species';
    case SUBSPECIES = 'subspecies';

    case VARIETY = 'variety';
    case FORM = 'form';

    /**
     * Rang nélküli klád.
     */
    case CLADE = 'clade';

    /**
     * Ismeretlen vagy nincs meghatározva.
     */
    case UNRANKED = 'unranked';
}
